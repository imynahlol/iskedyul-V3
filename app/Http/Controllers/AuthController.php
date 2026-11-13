<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller {
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'user_type' => [
                'required',
                Rule::in(['Student', 'Faculty']),
            ],

            'user_id' => [
                'required',
                'string',
                'max:20',
                'unique:users,user_id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->user_type === 'Student') {
                        if (! preg_match('/^20\d{2}-\d{5}-LQ-\d$/', $value)) {
                            $fail('Student ID must follow the format 20XX-XXXXX-LQ-X.');
                        }
                    }

                    if ($request->user_type === 'Faculty') {
                        if (! preg_match('/^\d{5}$/', $value)) {
                            $fail('Faculty ID must contain exactly 5 digits.');
                        }
                    }
                },
            ],

            'name' => ['required', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'user_type' => $validated['user_type'],
            'program' => $validated['program'] ?? null,
            'year_level' => $validated['year_level'] ?? null,
            'section' => $validated['section'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')
            ->with('success', 'Your account has been created successfully!');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'user_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('user_id', $validated['user_id'])
            ->whereIn('user_type', ['Student', 'Faculty'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors([
                    'user_id' => 'The ID or password you entered is incorrect.',
                ])
                ->onlyInput('user_id');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')
            ->with('success', 'Welcome back!');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }
}