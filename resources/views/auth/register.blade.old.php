<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
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

        <!-- State Selection -->
        <div class="mt-4">
            <x-input-label for="state" :value="__('State of Institution')" />
            <select id="state" name="state" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Select State') }}</option>
                @foreach($states as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>

        <!-- Institution Selection -->
        <div class="mt-4">
            <x-input-label for="institution" :value="__('Institution Name')" />
            <select id="institution" name="institution" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Select Institution') }}</option>
            </select>
            <x-input-error :messages="$errors->get('institution')" class="mt-2" />
        </div>

        <!-- User Documents (For Lecturers Only) -->
        <div id="lecturer-documents" class="mt-4">
            <x-input-label for="documents" :value="__('Upload Supporting Documents')" />
            <input id="documents" type="file" name="documents[]" multiple class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <x-input-error :messages="$errors->get('documents')" class="mt-2" />
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

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript for Dynamic Institution Fetching -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let stateDropdown = document.getElementById("state");
            let institutionDropdown = document.getElementById("institution");

            stateDropdown.addEventListener("change", function () {
                let stateId = this.value;
                institutionDropdown.innerHTML = '<option value="">Select Institution</option>'; // Reset

                if (stateId) {
                    fetch(`/institutions-by-state/${stateId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(institution => {
                                let option = document.createElement("option");
                                option.value = institution.id;
                                option.textContent = institution.name;
                                institutionDropdown.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error fetching institutions:", error));
                }
            });
        });
    </script>

</x-guest-layout>
