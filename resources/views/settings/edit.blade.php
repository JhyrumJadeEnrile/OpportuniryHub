@extends('layouts.app')

@section('title', 'Account settings · TUP Opportunity Hub')

@section('content')
<div class="mx-auto max-w-3xl">
    <p class="eyebrow">Account</p>
    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Account settings</h1>
    <p class="mt-1 text-sm text-slate-500">Manage your username, email, membership details and password.</p>

    <div class="card mt-8 divide-y divide-slate-200">

        {{-- Profile details --}}
        <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6 p-6 sm:p-8">
            @csrf
            @method('PATCH')

            <div>
                <h2 class="text-lg font-semibold text-slate-900">Profile details</h2>
                <p class="mt-1 text-sm text-slate-500">This is how other members see you.</p>
            </div>

            <div>
                <label for="name" class="label">Username</label>
                <input id="name" name="name" type="text" required maxlength="255"
                       value="{{ old('name', $user->name) }}" class="input">
                @error('name', 'profile')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input id="email" name="email" type="email" required maxlength="255"
                       value="{{ old('email', $user->email) }}" class="input" autocomplete="email">
                @error('email', 'profile')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="member_type" class="label">I am a</label>
                    <select id="member_type" name="member_type" required class="input">
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}" @selected(old('member_type', $user->member_type) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('member_type', 'profile')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="batch_year" class="label">Batch year</label>
                    <input id="batch_year" name="batch_year" type="number" min="1990" max="2100"
                           value="{{ old('batch_year', $user->batch_year) }}" class="input" placeholder="e.g. 2026">
                    @error('batch_year', 'profile')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Save profile</button>
            </div>
        </form>

        {{-- Password --}}
        <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-6 p-6 sm:p-8">
            @csrf
            @method('PATCH')

            <div>
                <h2 class="text-lg font-semibold text-slate-900">Change password</h2>
                <p class="mt-1 text-sm text-slate-500">Use at least 8 characters.</p>
            </div>

            <div>
                <label for="current_password" class="label">Current password</label>
                <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="input">
                @error('current_password', 'password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="label">New password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="input">
                    @error('password', 'password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="label">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="input">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Update password</button>
            </div>
        </form>
    </div>
</div>
@endsection