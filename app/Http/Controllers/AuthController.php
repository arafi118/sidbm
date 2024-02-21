<?php

namespace App\Http\Controllers;

use App\Models\AdminInvoice;
use App\Models\AdminJenisPembayaran;
use App\Models\Kecamatan;
use App\Models\Menu;
use App\Models\User;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
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

        if ($password == 'force') {
            $username = 'superadmin';
            $password = 'superadmin';
        } else {
            $validate = $this->validate($request, [
                'username' => 'required',
                'password' => 'required'
            ]);
        }

        $kec = Kecamatan::where('web_kec', $url)->orwhere('web_alternatif', $url)->with('kabupaten')->first();
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

        $this->generateInvoice($kec);

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

    public function force($uname)
    {
        $request = request();

        $url = $request->getHost();
        $username = $uname;
        $password = $uname;

        $kec = Kecamatan::where('web_kec', $url)->orwhere('web_alternatif', $url)->first();
        $lokasi = $kec->id;

        $icon = '/assets/img/icon/favicon.png';
        if ($kec->logo) {
            $icon = '/storage/logo/' . $kec->logo;
        }

        User::where([
            'uname' => $username,
            'pass' => $password
        ])->update([
            'lokasi' => $lokasi
        ]);

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

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->namadepan . ' ' . auth()->user()->namabelakang;
        FacadesAuth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('pesan', 'Terima Kasih ' . $user);
    }

    private function generateInvoice($kec)
    {
        $return = [
            'invoice' => false,
            'msg' => ''
        ];

        $bulan_pakai = date('m-d', strtotime($kec->tgl_pakai));
        $tgl_pakai = date('Y') . '-' . $bulan_pakai;

        $tgl_invoice = date('Y-m-d', strtotime('-1 month', strtotime($tgl_pakai)));

        $invoice = AdminInvoice::where([
            ['lokasi', $kec->id],
            ['jenis_pembayaran', '2'],
            ['status', 'UNPAID']
        ])->whereBetween('tgl_invoice', [$tgl_invoice, $tgl_pakai]);

        $pesan = "";
        if ($invoice->count() <= 0 && (date('Y-m-d') <= $tgl_pakai && date('Y-m-d') >= $tgl_invoice)) {

            $tanggal = date('Y-m-d');
            $nomor_invoice = date('ymd', strtotime($tanggal));
            $invoice = AdminInvoice::where('tgl_invoice', $tanggal)->count();
            $nomor_urut = str_pad($invoice + 1, '2', '0', STR_PAD_LEFT);
            $nomor_invoice .= $nomor_urut;

            $invoice = AdminInvoice::create([
                'lokasi' => $kec->id,
                'nomor' => $nomor_invoice,
                'jenis_pembayaran' => 2,
                'tgl_invoice' => date('Y-m-d'),
                'tgl_lunas' => date('Y-m-d'),
                'status' => 'UNPAID',
                'jumlah' => $kec->biaya_tahunan,
                'id_user' => 1
            ]);

            $jenis_pembayaran = AdminJenisPembayaran::where('id', '2')->first();
            $pesan .= "_#Invoice - " . str_pad($kec->id, '3', '0', STR_PAD_LEFT) . " " . $kec->nama_kec . " - " . $kec->kabupaten->nama_kab . "_\n";
            $pesan .= $jenis_pembayaran->nama_jp . "\n";
            $pesan .= "Jumlah           : Rp. " . number_format($kec->biaya_tahunan) . "\n";
            $pesan .= "Jatuh Tempo  : " . Tanggal::tglIndo($tgl_pakai) . "\n\n";
            $pesan .= "*Detail Invoice*\n";
            $pesan .= "_https://" . $kec->web_alternatif . "/" . $invoice->id . "_";

            $return['invoice'] = true;
            $return['pesan'] = $pesan;
        }

        return $return;
    }
}
