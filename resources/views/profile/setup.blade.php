<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Setup') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">4-Step Setup Wizard</h3>
                <p class="mt-1 text-sm text-gray-600">Step {{ $currentStep }} of 4</p>

                <div class="mt-4 grid grid-cols-4 gap-2 text-xs font-medium">
                    @foreach ([1 => 'Personal', 2 => 'Academic', 3 => 'Financial', 4 => 'Finish'] as $step => $label)
                        <div class="rounded px-3 py-2 text-center {{ $currentStep === $step ? 'bg-indigo-600 text-white' : ($currentStep > $step ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $step }}. {{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                @if ($currentStep === 1)
                    <form method="POST" action="{{ route('profile.setup.step1') }}" class="space-y-4">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="date_of_birth" value="Date of Birth" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="sex" value="Sex" />
                                <select id="sex" name="sex" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="">Select</option>
                                    @foreach (['Male', 'Female'] as $sex)
                                        <option value="{{ $sex }}" @selected(old('sex', $profile->sex) === $sex)>{{ $sex }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="home_address" value="Home Address" />
                                <x-text-input id="home_address" name="home_address" type="text" class="mt-1 block w-full" :value="old('home_address', $profile->home_address)" required />
                            </div>
                            <div>
                                <x-input-label for="city" value="City" />
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $profile->city)" required />
                            </div>
                            <div>
                                <x-input-label for="province" value="Province" />
                                <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" :value="old('province', $profile->province)" required />
                            </div>
                            <div>
                                <x-input-label for="zip_code" value="Zip Code" />
                                <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $profile->zip_code)" required />
                            </div>
                            <div>
                                <x-input-label for="mobile_number" value="Mobile Number" />
                                <x-text-input id="mobile_number" name="mobile_number" type="text" class="mt-1 block w-full" :value="old('mobile_number', $profile->mobile_number)" required />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>Save & Continue</x-primary-button>
                        </div>
                    </form>
                @elseif ($currentStep === 2)
                    <form method="POST" action="{{ route('profile.setup.step2') }}" class="space-y-4">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="university_name" value="University Name" />
                                <x-text-input id="university_name" name="university_name" type="text" class="mt-1 block w-full" :value="old('university_name', $profile->university_name)" required />
                            </div>
                            <div>
                                <x-input-label for="university_email" value="University Email" />
                                <x-text-input id="university_email" name="university_email" type="email" class="mt-1 block w-full" :value="old('university_email', $profile->university_email)" required />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="university_address" value="University Address" />
                                <x-text-input id="university_address" name="university_address" type="text" class="mt-1 block w-full" :value="old('university_address', $profile->university_address)" required />
                            </div>
                            <div>
                                <x-input-label for="course_program" value="Course Program" />
                                <x-text-input id="course_program" name="course_program" type="text" class="mt-1 block w-full" :value="old('course_program', $profile->course_program)" required />
                            </div>
                            <div>
                                <x-input-label for="student_number" value="Student Number" />
                                <x-text-input id="student_number" name="student_number" type="text" class="mt-1 block w-full" :value="old('student_number', $profile->student_number)" required />
                            </div>
                            <div>
                                <x-input-label for="year_level" value="Year Level" />
                                <x-text-input id="year_level" name="year_level" type="text" class="mt-1 block w-full" :value="old('year_level', $profile->year_level)" required />
                            </div>
                            <div>
                                <x-input-label for="semester" value="Semester" />
                                <x-text-input id="semester" name="semester" type="text" class="mt-1 block w-full" :value="old('semester', $profile->semester)" required />
                            </div>
                            <div>
                                <x-input-label for="academic_year" value="Academic Year" />
                                <x-text-input id="academic_year" name="academic_year" type="text" class="mt-1 block w-full" :value="old('academic_year', $profile->academic_year)" required />
                            </div>
                            <div>
                                <x-input-label for="gwa" value="GWA" />
                                <x-text-input id="gwa" name="gwa" type="number" step="0.01" min="1" max="5" class="mt-1 block w-full" :value="old('gwa', $profile->gwa)" required />
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('profile.setup', ['step' => 1]) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                            <x-primary-button>Save & Continue</x-primary-button>
                        </div>
                    </form>
                @elseif ($currentStep === 3)
                    <form method="POST" action="{{ route('profile.setup.step3') }}" class="space-y-4">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="monthly_household_income" value="Monthly Household Income" />
                                <x-text-input id="monthly_household_income" name="monthly_household_income" type="number" min="0" step="0.01" class="mt-1 block w-full" :value="old('monthly_household_income', $profile->monthly_household_income)" required />
                            </div>
                            <div>
                                <x-input-label for="num_dependents" value="Number of Dependents" />
                                <x-text-input id="num_dependents" name="num_dependents" type="number" min="0" class="mt-1 block w-full" :value="old('num_dependents', $profile->num_dependents)" required />
                            </div>
                            <div>
                                <x-input-label for="is_breadwinner" value="Breadwinner Status" />
                                <select id="is_breadwinner" name="is_breadwinner" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="">Select</option>
                                    @foreach (['Yes', 'No', 'Partial Contributor'] as $value)
                                        <option value="{{ $value }}" @selected(old('is_breadwinner', $profile->is_breadwinner) === $value)>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="is_4ps" value="4Ps Beneficiary" />
                                <select id="is_4ps" name="is_4ps" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="1" @selected((string) old('is_4ps', (int) $profile->is_4ps) === '1')>Yes</option>
                                    <option value="0" @selected((string) old('is_4ps', (int) $profile->is_4ps) === '0')>No</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="father_employment_status" value="Father Employment Status" />
                                <x-text-input id="father_employment_status" name="father_employment_status" type="text" class="mt-1 block w-full" :value="old('father_employment_status', $profile->father_employment_status)" />
                            </div>
                            <div>
                                <x-input-label for="mother_employment_status" value="Mother Employment Status" />
                                <x-text-input id="mother_employment_status" name="mother_employment_status" type="text" class="mt-1 block w-full" :value="old('mother_employment_status', $profile->mother_employment_status)" />
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('profile.setup', ['step' => 2]) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                            <x-primary-button>Save & Continue</x-primary-button>
                        </div>
                    </form>
                @else
                    <div class="space-y-4">
                        <p class="text-gray-700">You're almost done! Submit to complete your profile setup.</p>
                        <form method="POST" action="{{ route('profile.setup.submit') }}">
                            @csrf
                            <div class="flex justify-between">
                                <a href="{{ route('profile.setup', ['step' => 3]) }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                                <x-primary-button>Complete Setup</x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
