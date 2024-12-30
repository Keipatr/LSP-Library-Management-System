<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.member'); // Tampilkan view login khusus member
    }
    public function showStaffLoginForm()
    {
        return view('auth.staff'); // Halaman login untuk staff/admin
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Coba login
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/'); // Redirect ke halaman home
        }

        // Gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }
    public function staffLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            if (Auth::user()->role == 0) {
                return redirect()->route('catalog.index'); // Staff ke dashboard
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/'); // Redirect ke halaman home setelah logout
    }
}
