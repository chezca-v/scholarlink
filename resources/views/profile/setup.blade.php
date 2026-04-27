<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSetupController extends Controller
{
    /**
     * ENUM option lists — defined here so the Blade never hardcodes them.
     * Source of truth: applicant_profiles table ENUM definitions.
     */
    public static function incomeBrackets(): array
    {
        return [
            'below_10957'    => 'Below PHP 10,957 – Poor household',
            '10957_21914'    => 'PHP 10,957 – PHP 21,914 – Low income',
            '21914_43828'    => 'PHP 21,914 – PHP 43,828 – Lower middle income',
            '43828_76669'    => 'PHP 43,828 – PHP 76,669 – Middle income',
            '76669_131484'   => 'PHP 76,669 – PHP 131,484 – Upper middle income',
            'above_131484'   => 'Above PHP 131,484 – High income',
        ];
    }

    public static function parentEmploymentOptions(): array
    {
        return [
            'both_employed'     => 'Both parents employed',
            'one_employed'      => 'One parent employed',
            'self_employed'     => 'Self-employed / Negosyante',
            'ofw'               => 'Overseas Filipino Worker (OFW)',
            'unemployed'        => 'Unemployed / No Income',
            'solo_parent'       => 'Solo parent household',
            'parents_deceased'  => 'Parent/s deceased',
        ];
    }

    public static function fourPsOptions(): array
    {
        return [
            'active'            => 'Yes – Active beneficiary',
            'inactive'          => 'Yes – Inactive/Exited',
            'no'                => 'No',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
    }

    public static function yearLevelOptions(): array
    {
        return ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
    }

    public static function semesterOptions(): array
    {
        return ['1st Semester', '2nd Semester', 'Summer'];
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW the wizard (load existing profile if any)
    // ─────────────────────────────────────────────────────────────

    public function show(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch existing profile or instantiate empty model so the
        // Blade can always use $profile->field without null checks.
        $profile = ApplicantProfile::firstOrNew(['user_id' => $user->id]);

        $step = (int) $request->get('step', 1);
        $step = max(1, min(4, $step)); // clamp 1–4

        return view('profile.setup', [
            'user'                    => $user,
            'profile'                 => $profile,
            'currentStep'             => $step,
            'incomeBrackets'          => self::incomeBrackets(),
            'parentEmploymentOptions' => self::parentEmploymentOptions(),
            'fourPsOptions'           => self::fourPsOptions(),
            'yearLevelOptions'        => self::yearLevelOptions(),
            'semesterOptions'         => self::semesterOptions(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // SAVE step 1 — Personal Info
    // Fields: applicant_profiles (first_name, last_name, date_of_birth,
    //         sex, home_address, city, province, zip_code, mobile_number)
    //         users (email — stored on users table)
    // ─────────────────────────────────────────────────────────────

    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'sex'           => 'required|in:male,female,non_binary,prefer_not_to_say',
            'home_address'  => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'province'      => 'required|string|max:100',
            'zip_code'      => 'required|string|max:10',
            'mobile_number' => 'required|string|max:20',
            'email'         => 'required|email|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Save email to users table
        $user->update(['email' => $validated['email']]);

        // Upsert personal info to applicant_profiles
        ApplicantProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'sex'           => $validated['sex'],
                'home_address'  => $validated['home_address'],
                'city'          => $validated['city'],
                'province'      => $validated['province'],
                'zip_code'      => $validated['zip_code'],
                'mobile_number' => $validated['mobile_number'],
            ]
        );

        return redirect()->route('profile.setup', ['step' => 2]);
    }

    // ─────────────────────────────────────────────────────────────
    // SAVE step 2 — Academic Info
    // Fields: applicant_profiles (school_name, school_address,
    //         school_email, course, student_number, year_level,
    //         semester, academic_year, gwa)
    // ─────────────────────────────────────────────────────────────

    public function saveStep2(Request $request)
    {
        $validated = $request->validate([
            'school_name'    => 'required|string|max:255',
            'school_address' => 'required|string|max:255',
            'school_email'   => 'required|email|max:255',
            'course'         => 'required|string|max:255',
            'student_number' => 'required|string|max:50',
            'year_level'     => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'semester'       => 'required|in:1st Semester,2nd Semester,Summer',
            'academic_year'  => 'required|string|max:20',
            'gwa'            => 'required|numeric|min:1.00|max:5.00',
        ]);

        $user = Auth::user();

        ApplicantProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_name'    => $validated['school_name'],
                'school_address' => $validated['school_address'],
                'school_email'   => $validated['school_email'],
                'course'         => $validated['course'],
                'student_number' => $validated['student_number'],
                'year_level'     => $validated['year_level'],
                'semester'       => $validated['semester'],
                'academic_year'  => $validated['academic_year'],
                'gwa'            => $validated['gwa'],
            ]
        );

        return redirect()->route('profile.setup', ['step' => 3]);
    }

    // ─────────────────────────────────────────────────────────────
    // SAVE step 3 — Financial Background
    // Fields: applicant_profiles (income_bracket, num_dependents,
    //         is_breadwinner, parent_employment_status,
    //         is_4ps_beneficiary)
    // ─────────────────────────────────────────────────────────────

    public function saveStep3(Request $request)
    {
        $validated = $request->validate([
            'income_bracket'          => 'required|in:' . implode(',', array_keys(self::incomeBrackets())),
            'num_dependents'          => 'required|integer|min:1',
            'is_breadwinner'          => 'required|in:primary_earner,partial_contributor,no',
            'parent_employment_status'=> 'required|in:' . implode(',', array_keys(self::parentEmploymentOptions())),
            'is_4ps_beneficiary'      => 'required|in:' . implode(',', array_keys(self::fourPsOptions())),
        ]);

        $user = Auth::user();

        ApplicantProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'income_bracket'           => $validated['income_bracket'],
                'num_dependents'           => $validated['num_dependents'],
                'is_breadwinner'           => $validated['is_breadwinner'],
                'parent_employment_status' => $validated['parent_employment_status'],
                'is_4ps_beneficiary'       => $validated['is_4ps_beneficiary'],
            ]
        );

        return redirect()->route('profile.setup', ['step' => 4]);
    }

    // ─────────────────────────────────────────────────────────────
    // SUBMIT — Mark profile as complete
    // Fields: applicant_profiles (is_profile_complete)
    // ─────────────────────────────────────────────────────────────

    public function submit(Request $request)
    {
        $user = Auth::user();

        ApplicantProfile::where('user_id', $user->id)
            ->update(['is_profile_complete' => true]);

        return redirect()->route('applicant.dashboard')
            ->with('success', 'Your ScholarLink profile has been set up successfully!');
    }
}