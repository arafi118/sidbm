<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Menu;
use App\Models\User;
use App\Utils\Keuangan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Session;

class AuthController extends Controller
{
    public function index()
    {
        $keuangan = new Keuangan;

        if ($keuangan->startWith(request()->getHost(), 'master.sidbm')) {
            return redirect('/master');
        }

        $kec = Kecamatan::where('web_kec', explode('//', request()->url(''))[1])->orwhere('web_alternatif', explode('//', request()->url(''))[1])->first();
        if (!$kec) {
            abort(404);
        }

        $logo = '/assets/img/icon/favicon.png';
        if ($kec->logo) {
            $logo = '/storage/logo/' . $kec->logo;
        }

        return view('auth.login')->with(compact('kec', 'logo'));
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

        $icon = '/assets/img/icon/favicon.png';
        if ($kec->logo) {
            $icon = '/storage/logo/' . $kec->logo;
        }

        if ($username == 'superadmin' && $password == 'superadmin') {
            User::where([
                'uname' => $username,
                'pass' => $password
            ])->update([
                'lokasi' => $lokasi
            ]);
        }

        $user = User::where([['uname', $username], ['lokasi', $lokasi]])->first();
        if ($user) {
            if ($password === $user->pass) {
                if (Auth::loginUsingId($user->id)) {
                    $hak_akses = explode(',', $user->hak_akses);
                    $menu = Menu::where('parent_id', '0')->whereNotIn('id', $hak_akses)->where('aktif', 'Y')->with([
                        'child' => function ($query) use ($hak_akses) {
                            $query->whereNotIn('id', $hak_akses);
                        },
                        'child.child'  => function ($query) use ($hak_akses) {
                            $query->whereNotIn('id', $hak_akses);
                        }
                    ])->orderBy('sort', 'ASC')->orderBy('id', 'ASC')->get();

                    $angsuran = true;
                    if (in_array('19', $hak_akses) || in_array('21', $hak_akses)) {
                        $angsuran = false;
                    }

                    $request->session()->regenerate();
                    session([
                        'nama_lembaga' => str_replace('DBM ', '', $kec->nama_lembaga_sort),
                        'nama' => $user->namadepan . ' ' . $user->namabelakang,
                        'foto' => $user->foto,
                        'logo' => $kec->logo,
                        'lokasi' => $user->lokasi,
                        'lokasi_user' => $user->lokasi,
                        'menu' => $menu,
                        'icon' => $icon,
                        'angsuran' => $angsuran
                    ]);

                    return redirect('/dashboard')->with('pesan', 'Selamat Datang ' . $user->namadepan . ' ' . $user->namabelakang);
                }
            }
        }

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->namadepan . ' ' . auth()->user()->namabelakang;
        FacadesAuth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('pesan', 'Terima Kasih ' . $user);
    }
}
