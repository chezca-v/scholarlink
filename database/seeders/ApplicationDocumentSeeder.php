<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationDocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all applications
        $applications = Application::all();

        foreach ($applications as $application) {
            // Get documents for this applicant
            $documents = Document::where('user_id', $application->applicant_id)->get();

            foreach ($documents as $doc) {
                // Link them in the pivot table if not exists
                DB::table('application_documents')->updateOrInsert(
                    [
                        'application_id' => $application->id,
                        'document_id' => $doc->id,
                    ],
                    [
                        'submitted_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
