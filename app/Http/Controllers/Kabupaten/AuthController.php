<?php

namespace App\Http\Controllers\Kabupaten;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('kabupaten.auth.login');
    }

    public function login(Request $request)
    {
        $url = $request->getHost();
        $data = $request->only([
            'username', 'password'
        ]);

        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $kab = Kabupaten::where('web_kab', $url)->orwhere('web_kab_alternatif', $url)->first();
        $login_kab = Kabupaten::where('uname', $data['username'])->first();
        if ($login_kab) {
            if ($login_kab->pass == $kab->pass && $login_kab->pass === $data['password']) {
                if (Auth::guard('kab')->loginUsingId($login_kab->id)) {
                    $request->session()->regenerate();

                    $kecamatan = Kecamatan::where('kd_kab', $login_kab->kd_kab)->orderBy('nama_kec', 'ASC')->get();
                    session([
                        'nama_kab' => ucwords(strtolower($login_kab->nama_kab)),
                        'kecamatan' => $kecamatan
                    ]);

                    return redirect('/kab/dashboard')->with([
                        'pesan' => 'Login Kabupaten ' . ucwords(strtolower($login_kab->nama_kab)) . ' Berhasil'
                    ]);
                }
            }
        }

        $error = 'Username atau Password Salah';
        return redirect()->back()->with('error', $error);
    }

    public function logout(Request $request)
    {
        $user = auth()->guard('kab')->user()->nama_kab;
        Auth::guard('kab')->logout();

        return redirect('/kab')->with('pesan', 'Terima Kasih');
    }
}
