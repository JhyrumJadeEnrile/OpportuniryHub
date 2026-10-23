<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'member_type' => ['required', 'in:'.implode(',', array_keys(User::TYPES))],
            'program'     => ['nullable', 'string', 'max:120'],
            'batch_year'  => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'        => 'Please enter your full name.',
            'email.required'       => 'Please enter your email address.',
            'email.email'          => 'Please enter a valid email address.',
            'email.unique'         => 'An account with this email already exists.',
            'member_type.required' => 'Please select what you are (student, fresh grad or alumni).',
            'password.required'    => 'Please enter a password.',
            'password.confirmed'   => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'member_type' => $request->member_type,
            'program'     => $request->program,
            'batch_year'  => $request->batch_year,
            'password'    => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Send the user to the login page instead of logging them in.
        return redirect()->route('login')->with('status', 'Account created! Please log in.');
    }
}