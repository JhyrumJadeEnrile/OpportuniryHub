===<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="member_type" :value="__('I am a')" />
            <select id="member_type" name="member_type" required
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select one</option>
                @foreach (\App\Models\User::TYPES as $key => $label)
                    <option value="{{ $key }}" @selected(old('member_type') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('member_type')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="program" :value="__('Program (e.g. BTVTE-ICT)')" />
            <x-text-input id="program" class="block mt-1 w-full" type="text" name="program" :value="old('program')" />
            <x-input-error :messages="$errors->get('program')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="batch_year" :value="__('Batch year (graduation year)')" />
            <x-text-input id="batch_year" class="block mt-1 w-full" type="number" name="batch_year" :value="old('batch_year')" min="1990" max="2100" />
            <x-input-error :messages="$errors->get('batch_year')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Already registered?</a>
            <x-primary-button class="ms-4">Register</x-primary-button>
        </div>
    </form>
</x-guest-layout>