<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $url = $request->getHost();
        $username = htmlspecialchars($request->username);
        $password = $request->password;

        $validate = $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $kec = Kecamatan::where('web_kec', $url)->orwhere('web_alternatif', $url)->first();
        $lokasi = $kec->id;

        $user = User::where([['uname', $username], ['lokasi', $lokasi]])->first();
        if ($user) {
            if ($password === $user->pass) {
                if (Auth::loginUsingId($user)) {
                    Session::put('nama_lembaga', str_replace('DBM ', '', $kec->nama_lembaga_sort));
                    Session::put('nama', auth()->user()->namadepan . ' ' . auth()->user()->namabelakang);
                    Session::put('foto', auth()->user()->foto);

                    echo '<script>
                    window.open("/piutang_jasa");
                    window.location.href="/dashboard";
                    </script>';

                    return '';
                }
            }
        }

        return redirect()->back();
    }
}
