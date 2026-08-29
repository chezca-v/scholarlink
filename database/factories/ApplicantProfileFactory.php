<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicantProfile>
 */
class ApplicantProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'avatar_url' => null,
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'home_address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'mobile_number' => $this->faker->phoneNumber(),
            'sms_opted_in' => false,
            'phone_verified_at' => null,
            'university_name' => $this->faker->company().' University',
            'university_address' => $this->faker->address(),
            'university_email' => $this->faker->safeEmail(),
            'course_program' => $this->faker->word(),
            'student_number' => $this->faker->unique()->numerify('STU-########'),
            'year_level' => $this->faker->randomElement([1, 2, 3, 4]),
            'semester' => $this->faker->randomElement(['1st', '2nd']),
            'academic_year' => now()->year.'-'.(now()->year + 1),
            'gwa' => $this->faker->numberBetween(200, 400) / 100,
            'monthly_household_income' => $this->faker->numberBetween(10000, 100000),
            'num_dependents' => $this->faker->numberBetween(0, 6),
            'is_breadwinner' => $this->faker->randomElement(['Yes', 'No', 'Partial Contributor']),
            'is_4ps' => $this->faker->boolean(),
            'father_employment_status' => $this->faker->randomElement(['Employed', 'Unemployed', 'Retired', 'Deceased']),
            'mother_employment_status' => $this->faker->randomElement(['Employed', 'Unemployed', 'Retired', 'Deceased']),
            'profile_completed_at' => null,
            'gwa_scale' => $this->faker->randomElement(['college', 'shs']),
        ];
    }
}
