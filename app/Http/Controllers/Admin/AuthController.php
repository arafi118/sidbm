<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->only([
            'gmail', 'password'
        ]);

        $validate = $request->validate([
            'gmail' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('master')->attempt($validate)) {
            $request->session()->regenerate();

            session([
                'admin' => auth()->guard('master')->user()->nama_lengkap
            ]);

            return redirect()->intended('/master')->with('pesan', 'Selamat Datang ' . auth()->guard('master')->user()->nama_lengkap);
        }
        return back()->with('error', 'Email atau Password Salah.');
    }

    public function logout(Request $request)
    {
        $user = auth()->guard('master')->user()->nama_lengkap;
        Auth::guard('master')->logout();

        return redirect('/master')->with('pesan', 'Terima Kasih ' . $user);
    }
}
