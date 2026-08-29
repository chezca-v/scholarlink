<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\EvaluatorAssignment;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarshipLifecycleE2ETest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_end_to_end_application_lifecycle(): void
    {
        Storage::fake('public');

        // 1. ADMIN CREATES SCHOLARSHIP
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/scholarships', [
            'name' => 'DOST S&T Excellence Grant',
            'provider_name' => 'DOST',
            'description' => 'A grant for outstanding STEM students.',
            'gpa_requirement' => 3.00,
            'income_bracket' => 'Below 300,000',
            'slots' => 10,
            'eligibility' => 'Filipino citizen, enrolled in a STEM course.',
            'benefits' => 'Full tuition and a monthly stipend.',
            'requirements' => 'Transcript of records, certificate of indigency.',
            'open_date' => now()->toDateString(),
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'open',
            'blind_screening' => 1,
            'weight_gpa' => 60,
            'weight_income' => 40,
        ])->assertRedirect(route('admin.scholarships.index'));

        $scholarship = Scholarship::where('name', 'DOST S&T Excellence Grant')->first();
        $this->assertNotNull($scholarship);
        $this->assertEquals($admin->id, $scholarship->created_by);

        // 2. APPLICANT SUBMITS A 3-STEP APPLICATION WITH REQUIRED DOCUMENTS
        $applicant = User::factory()->create([
            'role' => 'applicant',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
        ]);

        ApplicantProfile::factory()->create([
            'user_id' => $applicant->id,
            'profile_completed_at' => now(),
            'gwa' => 1.75, // meets the 3.00 requirement (lower GWA is better in PH system)
            'monthly_household_income' => 20000, // 240,000/yr, under the 300,000 bracket
        ]);

        $requiredSlugs = ['proof-of-enrollment', 'report-card', 'id-photo', 'income-tax-return', 'barangay-indigency'];
        $uploads = [];
        foreach ($requiredSlugs as $slug) {
            $uploads[$slug] = UploadedFile::fake()->create("{$slug}.pdf", 200, 'application/pdf');
        }

        $this->actingAs($applicant)
            ->post("/apply/{$scholarship->id}", [
                'scholarship_id' => $scholarship->id,
                'uploads' => $uploads,
            ])
            ->assertSessionHas('application_submitted');

        $application = Application::where('applicant_id', $applicant->id)
            ->where('scholarship_id', $scholarship->id)
            ->first();

        $this->assertNotNull($application);
        $this->assertEquals('pending', $application->status);
        $this->assertEquals('submitted', $application->stage);
        $this->assertNotNull($application->reference_code);

        // 3. ADMIN ASSIGNS THE APPLICATION TO AN EVALUATOR
        // (this also grants the evaluator scholarship-level access via
        // EvaluatorAssignment — see AdminController::assign())
        $evaluator = User::factory()->create(['role' => 'evaluator']);

        $this->actingAs($admin)
            ->patch("/admin/applications/{$application->id}/assign", ['evaluator_id' => $evaluator->id])
            ->assertRedirect();

        $application->refresh();
        $this->assertEquals('under_review', $application->status);
        $this->assertTrue(
            Evaluation::where('application_id', $application->id)->where('evaluator_id', $evaluator->id)->exists()
        );
        $this->assertTrue(
            EvaluatorAssignment::where('evaluator_id', $evaluator->id)->where('scholarship_id', $scholarship->id)->exists()
        );

        // 4. EVALUATOR OPENS THE BLIND REVIEW — APPLICANT IDENTITY MUST BE MASKED
        $this->actingAs($evaluator)
            ->get("/evaluator/review/{$application->id}")
            ->assertStatus(200)
            ->assertDontSee('Juan Dela Cruz');

        // 5. EVALUATOR APPROVES ALL SUBMITTED DOCUMENTS AND THE DECISION
        // (scores are computed server-side from the applicant's profile)
        $documentApprovals = $application->applicationDocuments->mapWithKeys(
            fn ($applicationDocument) => [$applicationDocument->id => ['status' => 'approved']]
        )->all();

        $this->actingAs($evaluator)
            ->post("/evaluator/review/{$application->id}", [
                'decision' => 'approved',
                'notes' => 'Meets all academic and financial requirements.',
                'documents' => $documentApprovals,
            ])
            ->assertRedirect(route('evaluator.queue'));

        // 6. VERIFY FINAL DATABASE STATE
        $application->refresh();
        $this->assertEquals('approved', $application->status);
        $this->assertEquals('decided', $application->stage);
        $this->assertNotNull($application->decided_at);
        $this->assertEquals('pending', $application->offer_status);

        $evaluation = Evaluation::where('application_id', $application->id)
            ->where('evaluator_id', $evaluator->id)
            ->first();

        $this->assertEquals(100.00, $evaluation->gpa_score);
        $this->assertEquals(100.00, $evaluation->income_score);
        $this->assertEquals(100.00, $evaluation->final_score); // (100 * 0.6) + (100 * 0.4)

        // 7. APPLICANT ACCEPTS THE SCHOLARSHIP OFFER
        $this->actingAs($applicant)
            ->post("/applicant/applications/{$application->id}/offer", ['action' => 'accept'])
            ->assertRedirect(route('dashboard'));

        $application->refresh();
        $this->assertEquals('accepted', $application->offer_status);
    }
}
