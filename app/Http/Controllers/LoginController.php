<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            return back()->withInput()->with('error', 'Email atau password salah.');
        }

        if ($admin->locked_until && Carbon::now()->lt($admin->locked_until)) {
            $lockedSeconds = Carbon::now()->diffInSeconds($admin->locked_until);

            return back()->withInput()
                ->with('error', 'Akun Anda dikunci. Silakan tunggu beberapa saat.')
                ->with('locked_seconds', $lockedSeconds);
        }

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin->login_attempts = 0;
            $admin->locked_until = null;
            $admin->save();

            return redirect()->intended('/users');
        }

        $admin->increment('login_attempts');

        if ($admin->login_attempts >= 3) {
            $admin->locked_until = Carbon::now()->addMinutes(5);
            $admin->login_attempts = 0;
            $admin->save();

            return back()->withInput()
                ->with('error', 'Akun Anda dikunci karena 3 kali gagal login. Silakan tunggu 5 menit.')
                ->with('locked_seconds', 300); // 5 menit = 300 detik
        }

        $admin->save();

        return back()->withInput()
            ->with('error', 'Email atau password salah. Percobaan ke-' . $admin->login_attempts . ' dari 3.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
