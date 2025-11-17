@section('title', 'Register - ' . config('app.name', 'Tertab') . ' | Join Our Reference Platform')
@section('description', 'Create your Tertab account to get verified academic and professional references from trusted lecturers. Free registration for students and professionals.')
@section('keywords', 'tertab register, sign up, create account, academic references registration, professional references signup, student registration, lecturer registration')
@section('robots', 'index, follow')

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registration-form">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Account Type -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Account Type')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Click to Select') }}</option>
                <option value="lecturer">{{ __('Lecturer') }}</option>
                <option value="student">{{ __('Student') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- reCAPTCHA -->
        <div class="mt-4">
            <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <span class="text-red-500 text-sm mt-2" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="register-btn">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Verification Dialog -->
    <div id="verification-dialog" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" x-data="{ open: false }" x-show="open" @verification-dialog.window="open = true">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Verify Your Identity</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Please provide the following information to verify your account.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.submit') }}" enctype="multipart/form-data" class="mt-5 sm:mt-6">
                    @csrf
                    
                    <!-- Full Name -->
                    <div class="mb-4">
                        <x-input-label for="verification_name" :value="__('Full Name')" />
                        <x-text-input id="verification_name" class="block mt-1 w-full" type="text" name="verification_name" required />
                        <x-input-error :messages="$errors->get('verification_name')" class="mt-2" />
                    </div>

                    <!-- School Email -->
                    <div class="mb-4">
                        <x-input-label for="school_email" :value="__('School Email')" />
                        <x-text-input id="school_email" class="block mt-1 w-full" type="email" name="school_email" required />
                        <x-input-error :messages="$errors->get('school_email')" class="mt-2" />
                    </div>

                    <!-- Institution -->
                    <div class="mb-4">
                        <x-input-label for="institution" :value="__('Institution')" />
                        <x-text-input id="institution" class="block mt-1 w-full" type="text" name="institution" required />
                        <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                    </div>

                    <!-- Position/Department -->
                    <div class="mb-4">
                        <x-input-label for="position" :value="__('Position/Department')" />
                        <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" required />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <!-- ID Card -->
                    <div class="mb-4">
                        <x-input-label for="id_card" :value="__('ID Card')" />
                        <input id="id_card" type="file" name="id_card" class="block mt-1 w-full" accept="image/*,.pdf" required />
                        <x-input-error :messages="$errors->get('id_card')" class="mt-2" />
                    </div>

                    <!-- Additional Documents -->
                    <div class="mb-4">
                        <x-input-label for="additional_documents" :value="__('Additional Documents (Optional)')" />
                        <input id="additional_documents" type="file" name="additional_documents[]" class="block mt-1 w-full" accept="image/*,.pdf" multiple />
                        <x-input-error :messages="$errors->get('additional_documents')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <x-input-label for="notes" :value="__('Additional Notes (Optional)')" />
                        <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <x-primary-button type="submit" class="w-full justify-center">
                            {{ __('Submit Verification') }}
                        </x-primary-button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="open = false">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registration-form');
            const submitBtn = document.getElementById('register-btn');
            let isSubmitting = false;

            // Prevent double submission
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Registering...';
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Register';
                }, 3000);
            });

            // Only show verification dialog if there's a success parameter
            // This should be triggered from your controller after successful registration
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('registered') === 'success') {
                window.dispatchEvent(new CustomEvent('verification-dialog'));
            }
        });
    </script>
</x-guest-layout>