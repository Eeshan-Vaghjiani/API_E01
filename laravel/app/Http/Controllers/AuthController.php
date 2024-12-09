<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TwoFACode;
use Exception;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $recipient = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->send2FACode($recipient);

        return redirect()->route('verify-2fa')->with('recipient', $recipient);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $recipient = Auth::user();
            $this->send2FACode($recipient);
            return redirect()->route('verify-2fa')->with('recipient', $recipient);
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function show2faForm()
    {
        return view('auth.verify-2fa');
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $recipient = $request->session()->get('recipient');

        if ($recipient && $request->code === $recipient->two_fa_code) {
            Auth::login($recipient);
            return redirect()->route('home');
        }

        return back()->withErrors(['code' => 'Invalid 2FA code.']);
    }

    protected function send2FACode($recipient)
    {
        $code = Str::random(6);
        $recipient->two_fa_code = $code;
        $recipient->save();

        try {
            Mail::to($recipient->email)->send(new TwoFACode($recipient, $code));
        } catch (Exception $e) {
            \Log::error('Failed to send 2FA code: ' . $e->getMessage());
        }
    }
}
