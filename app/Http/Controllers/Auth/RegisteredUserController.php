<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $randomNumber = Str::random(5);
        // Using email to generate a unique code for qrcode attendance which can be decoded back to get the email
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'qrcode' => hexdec(substr(md5($request->email), 0, 7)),
        ]);

        if ($user) {
            // Registration was successful
            $response = ([
                'success' => true,
                'message' => 'Registration successful',
            ]);
            return back()->with('success', 'User Created Successfully');
        } else {
            // Registration failed
            $response = ([
                'success' => false,
                'message' => 'Registration failed',
            ]);
            return back()->with('error', 'Registration failed');
        }
    }
}
