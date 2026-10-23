<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.edit', [
            'user'  => $request->user(),
            'types' => User::TYPES,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validateWithBag('profile', [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'member_type' => ['required', 'in:'.implode(',', array_keys(User::TYPES))],
            'batch_year'  => ['nullable', 'integer', 'min:1990', 'max:2100'],
        ], [
            'email.unique' => 'That email is already used by another account.',
        ]);

        $user->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.confirmed' => 'New passwords do not match.',
        ]);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('status', 'Password changed.');
    }
}