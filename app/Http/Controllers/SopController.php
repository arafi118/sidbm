<?php

namespace App\Http\Controllers;

use App\Models\AdminInvoice;
use App\Models\AkunLevel1;
use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\TandaTanganLaporan;
use App\Models\User;
use App\Utils\Pinjaman;
use App\Utils\Tanggal;
use Cookie;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Session;
use Yajra\DataTables\DataTables;

class SopController extends Controller
{
    public function index()
    {
        $api = env('APP_API', 'https://api-whatsapp.sidbm.net');

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('ttd')->first();
        $token = "DBM-" . str_pad($kec->id, 4, '0', STR_PAD_LEFT);

        $title = "Personalisasi SOP";
        return view('sop.index')->with(compact('title', 'kec', 'api', 'token'));
    }

    public function coa()
    {
        $title = "Chart Of Account (CoA)";

        if (request()->ajax()) {
            $akun1 = AkunLevel1::with([
                'akun2',
                'akun2.akun3',
                'akun2.akun3.rek'
            ])->get();

            $coa = [];
            foreach ($akun1 as $ak1) {
                $akun_level_1 = [
                    "id" => $ak1->kode_akun,
                    "text" => $ak1->kode_akun . '. ' . $ak1->nama_akun,
                    'children' => []
                ];

                foreach ($ak1->akun2 as $ak2) {
                    $akun2 = [
                        "id" => $ak2->kode_akun,
                        "text" => $ak2->kode_akun . '. ' . $ak2->nama_akun,
                        'children' => []
                    ];

                    foreach ($ak2->akun3 as $ak3) {
                        $akun3 = [
                            "id" => $ak3->kode_akun,
                            "text" => $ak3->kode_akun . '. ' . $ak3->nama_akun,
                            'children' => []
                        ];

                        foreach ($ak3->rek as $rek) {
                            $akun4 = [
                                "id" => $rek->kode_akun,
                                "text" => $rek->kode_akun . '. ' . $rek->nama_akun,
                            ];

                            array_push($akun3['children'], $akun4);
                        }
                        array_push($akun2['children'], $akun3);
                    }
                    array_push($akun_level_1['children'], $akun2);
                }
                array_push($coa, $akun_level_1);
            }

            return response()->json($coa);
        }

        return view('sop.coa')->with(compact('title'));
    }

    public function updateCoa(Request $request, Rekening $rekening)
    {
        $data = $request->only([
            'id_akun',
            'nama_akun'
        ]);

        $nama_akun = str_replace($data['id_akun'] . '. ', '', $data['nama_akun']);
        $nama_akun = trim($nama_akun);

        if ($rekening->nama_akun != $nama_akun && $rekening->kode_akun == $data['id_akun']) {
            Rekening::where('kode_akun', $rekening->kode_akun)->update([
                'nama_akun' => $nama_akun,
            ]);

            return response()->json([
                'success' => true,
                'msg' => 'Akun dengan kode ' . $data['id_akun'] . ' berhasil diperbarui',
                'nama_akun' => $data['id_akun'] . '. ' . $nama_akun,
                'id' => $data['id_akun'],
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Akun gagal diperbarui'
        ]);
    }

    public function lembaga(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'nama_bumdesma',
            'nomor_badan_hukum',
            'telpon',
            'email',
            'alamat',
            'peraturan_desa',
            'npwp',
            'tanggal_npwp'
        ]);

        $validate = Validator::make($data, [
            'nama_bumdesma' => 'required',
            'nomor_badan_hukum' => 'required',
            'telpon' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'peraturan_desa' => 'required',
            'npwp' => 'required',
            'tanggal_npwp' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $calk = [
            'peraturan_desa' => $request->peraturan_desa,
            "D" => [
                "1" => [
                    "d" => [
                        "1" => 0,
                        "2" => 0,
                        "3" => 0
                    ]
                ],
                "2" => [
                    "a" => 0,
                    "b" => 0,
                    "c" => 0
                ]
            ]
        ];

        $kecamatan = Kecamatan::where('id', $kec->id)->update([
            'nama_lembaga_sort' => ucwords(strtolower($data['nama_bumdesma'])),
            'nama_lembaga_long' => ucwords(strtolower($data['nama_bumdesma'])),
            'nomor_bh' => $data['nomor_badan_hukum'],
            'telpon_kec' => $data['telpon'],
            'email_kec' => $data['email'],
            'alamat_kec' => $data['alamat'],
            'npwp' => $data['npwp'],
            'tgl_npwp' => Tanggal::tglNasional($data['tanggal_npwp']),
            'calk' => json_encode($calk),
        ]);

        Session::put('nama_lembaga', ucwords(strtolower($data['nama_bumdesma'])));

        return response()->json([
            'success' => true,
            'msg' => 'Identitas Lembaga Berhasil Diperbarui.',
            'nama_lembaga' => ucwords(strtolower($data['nama_bumdesma']))
        ]);
    }

    public function pengelola(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'sebutan_pengawas',
            'sebutan_verifikator',
            'kepala_lembaga',
            'kabag_administrasi',
            'kabag_keuangan',
            'bkk_bkm'
        ]);

        $validate = Validator::make($data, [
            'sebutan_pengawas' => 'required',
            'sebutan_verifikator' => 'required',
            'kepala_lembaga' => 'required',
            'kabag_administrasi' => 'required',
            'kabag_keuangan' => 'required',
            'bkk_bkm' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $kecamatan = Kecamatan::where('id', $kec->id)->update([
            'nama_bp_long' => ucwords(strtolower($data['sebutan_pengawas'])),
            'nama_bp_sort' => ucwords(strtolower($data['sebutan_pengawas'])),
            'nama_tv_long' => ucwords(strtolower($data['sebutan_verifikator'])),
            'nama_tv_sort' => ucwords(strtolower($data['sebutan_verifikator'])),
            'sebutan_level_1' => ucwords(strtolower($data['kepala_lembaga'])),
            'sebutan_level_2' => ucwords(strtolower($data['kabag_administrasi'])),
            'sebutan_level_3' => ucwords(strtolower($data['kabag_keuangan'])),
            'disiapkan' => ucwords(strtolower($data['bkk_bkm']))
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sebutan Pengelola Berhasil Diperbarui.',
        ]);
    }

    public function pinjaman(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'default_jasa',
            'default_jangka',
            'pembulatan',
            'sistem'
        ]);

        $validate = Validator::make($data, [
            'default_jasa' => 'required',
            'default_jangka' => 'required',
            'pembulatan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $data['pembulatan'] = "$data[sistem]$data[pembulatan]";

        $kecamatan = Kecamatan::where('id', $kec->id)->update([
            'def_jasa' => $data['default_jasa'],
            'def_jangka' => $data['default_jangka'],
            'pembulatan' => $data['pembulatan'],
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sistem Pinjaman Berhasil Diperbarui.',
        ]);
    }

    public function asuransi(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'nama_asuransi',
            'jenis_asuransi',
            'usia_maksimal',
            'presentase_premi',
        ]);

        $validate = Validator::make($data, [
            'nama_asuransi' => 'required',
            'jenis_asuransi' => 'required',
            'usia_maksimal' => 'required',
            'presentase_premi' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $kecamatan = Kecamatan::where('id', $kec->id)->update([
            'nama_asuransi_p' => $data['nama_asuransi'],
            'pengaturan_asuransi' => $data['jenis_asuransi'],
            'usia_mak' => $data['usia_maksimal'],
            'besar_premi' => $data['presentase_premi'],
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Pengaturan Asuransi Berhasil Diperbarui.',
        ]);
    }

    public function spk(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'spk'
        ]);

        $validate = Validator::make($data, [
            'spk' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $spk = json_encode($data['spk']);

        $kecamatan = Kecamatan::where('id', $kec->id)->update([
            'redaksi_spk' => $spk
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Redaksi Dokumen SPK Berhasil Diperbarui.',
        ]);
    }

    public function logo(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'logo_kec'
        ]);

        $validate = Validator::make($data, [
            'logo_kec' => 'required|image|mimes:jpg,png,jpeg|max:4096'
        ]);

        if ($request->hasFile('logo_kec')) {
            $extension = $request->file('logo_kec')->getClientOriginalExtension();

            $filename = time() . '_' . $kec->id . '_' . date('Ymd') . '.' . $extension;
            $path = $request->file('logo_kec')->storeAs('logo', $filename, 'public');

            if (Storage::exists('logo/' . $kec->logo)) {
                if ($kec->logo != '1.png') {
                    Storage::delete('logo/' . $kec->logo);
                }
            }

            $kecamatan = Kecamatan::where('id', $kec->id)->update([
                'logo' => str_replace('logo/', '', $path)
            ]);

            Session::put('logo', str_replace('logo/', '', $path));
            return response()->json([
                'success' => true,
                'msg' => 'Logo berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Logo gagal diperbarui'
        ]);
    }

    public function whatsapp($token)
    {
        User::where('lokasi', Session::get('lokasi'))->update([
            'ip' => $token
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sukses'
        ]);
    }

    public function beritaAcara(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'ba'
        ]);

        $validate = Validator::make($data, [
            'ba' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $ba = $data['ba'];
        $ba = str_replace("<p>", "<div>", $ba);
        $ba = str_replace("</p>", "</div>", $ba);

        if ($ba != "<div><br></div>") {
            $ba = json_encode($ba);
            $kecamatan = Kecamatan::where('id', $kec->id)->update([
                'berita_acara' => $ba
            ]);

            return response()->json([
                'success' => true,
                'msg' => 'Berita Acara Berhasil Diperbarui.',
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Berita Acara Gagal Diperbarui.',
        ]);
    }

    public function ttdPelaporan()
    {
        $title = "Pengaturan Tanda Tangan Pelaporan";
        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('ttd')->first();
        $ttd = TandaTanganLaporan::where([['lokasi', Session::get('lokasi')]])->first();

        $tanggal = false;
        if ($ttd) {
            $str = strpos($ttd->tanda_tangan_pelaporan, '{tanggal}');

            if ($str !== false) {
                $tanggal = true;
            }
        }

        return view('sop.partials.ttd_pelaporan')->with(compact('title', 'kec', 'tanggal'));
    }

    public function ttdSpk()
    {
        $title = "Pengaturan Tanda Tangan SPK";
        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('ttd')->first();
        $keyword = Pinjaman::keyword();

        return view('sop.partials.ttd_spk')->with(compact('title', 'kec', 'keyword'));
    }

    public function simpanTtdPelaporan(Request $request)
    {
        $data = $request->only([
            'field',
            'tanda_tangan'
        ]);

        if ($data['field'] == 'tanda_tangan_pelaporan') {
            $data['tanda_tangan'] = preg_replace('/<table[^>]*>/', '<table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">', $data['tanda_tangan'], 1);
        } else {
            $data['tanda_tangan'] = preg_replace('/<table[^>]*>/', '<table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;">', $data['tanda_tangan'], 1);
        }
        $data['tanda_tangan'] = preg_replace('/height:\s*[^;]+;?/', '', $data['tanda_tangan']);

        $data['tanda_tangan'] = str_replace('colgroup', 'tr', $data['tanda_tangan']);
        $data['tanda_tangan'] = preg_replace('/<col([^>]*)>/', '<td$1>&nbsp;</td>', $data['tanda_tangan']);

        $ttd = TandaTanganLaporan::where('lokasi', Session::get('lokasi'))->count();
        if ($ttd <= 0) {
            $insert = [
                'lokasi' => Session::get('lokasi')
            ];

            if ($data['field'] == 'tanda_tangan_pelaporan') {
                $insert['tanda_tangan_spk'] = '';
                $insert['tanda_tangan_pelaporan'] = json_encode($data['tanda_tangan']);
            } else {
                $insert['tanda_tangan_pelaporan'] = '';
                $insert['tanda_tangan_spk'] = json_encode($data['tanda_tangan']);
            }

            $tanda_tangan = TandaTanganLaporan::create($insert);
        } else {
            // dd($data['tanda_tangan']);
            $tanda_tangan = TandaTanganLaporan::where('lokasi', Session::get('lokasi'))->update([
                $data['field'] => json_encode($data['tanda_tangan'])
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => ucwords(str_replace('_', ' ', $data['field'])) . ' Berhasil diperbarui'
        ]);
    }

    public function invoice()
    {
        if (request()->ajax()) {
            $invoice = AdminInvoice::where('lokasi', Session::get('lokasi'))->with('jp')->withSum('trx', 'jumlah')->get();

            return DataTables::of($invoice)
                ->editColumn('tgl_invoice', function ($row) {
                    return Tanggal::tglIndo($row->tgl_invoice);
                })
                ->editColumn('jumlah', function ($row) {
                    return number_format($row->jumlah);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'PAID') {
                        return '<span class="badge badge-success">' . $row->status . '</span>';
                    }

                    return '<span class="badge badge-danger">' . $row->status . '</span>';
                })
                ->addColumn('saldo', function ($row) {
                    if ($row->trx_sum_jumlah) {
                        return number_format($row->jumlah - $row->trx_sum_jumlah);
                    }

                    return number_format($row->jumlah);
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $title = 'Daftar Invoice';
        return view('sop.invoice')->with(compact('title'));
    }

    public function calk(Request $request, Kecamatan $kec)
    {
        $data = $request->only([
            'peraturan_desa',
            'bantuan_rumah_tangga',
            'pengembangan_kapasitas',
            'pelatihan_masyarakat',
            'peningkatan_modal',
            'penambahan_investasi',
            'pendirian_unit_usaha',
        ]);

        $validate = Validator::make($data, [
            'peraturan_desa' => 'required',
            'bantuan_rumah_tangga' => 'required',
            'pengembangan_kapasitas' => 'required',
            'pelatihan_masyarakat' => 'required',
            'peningkatan_modal' => 'required',
            'penambahan_investasi' => 'required',
            'pendirian_unit_usaha' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $data = [
            'peraturan_desa' => $request->peraturan_desa,
            "D" => [
                "1" => [
                    "d" => [
                        "1" => $request->bantuan_rumah_tangga,
                        "2" => $request->pengembangan_kapasitas,
                        "3" => $request->pelatihan_masyarakat
                    ]
                ],
                "2" => [
                    "a" => str_replace(',', '', $request->peningkatan_modal),
                    "b" => str_replace(',', '', $request->penambahan_investasi),
                    "c" => str_replace(',', '', $request->pendirian_unit_usaha)
                ]
            ]
        ];

        $kec = Kecamatan::where('id', $kec->id)->update([
            'calk' => json_encode($data)
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Pengaturan CALK Berhasil Diperbarui.',
        ]);
    }

    public function pesanWhatsapp(Request $request, Kecamatan $kec)
    {
        if ($kec->id != Session::get('lokasi')) {
            abort(404);
        }

        $data = $request->only([
            'tagihan',
            'angsuran'
        ]);

        $validate = Validator::make($data, [
            'tagihan' => 'required',
            'angsuran' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $wa = [
            'tagihan' => $data['tagihan'],
            'angsuran' => $data['angsuran']
        ];

        Kecamatan::where('id', $kec->id)->update([
            'whatsapp' => $wa
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Pengaturan Pesan Whatsapp Berhasil Diperbarui.',
        ]);
    }

    public function detailInvoice($inv)
    {
        $inv = AdminInvoice::where('idv', $inv)->with('jp')->first();

        $title = 'Invoice #' . $inv->nomor . ' - ' . $inv->jp->nama_jp;
        return view('sop.detail_invoice')->with(compact('title', 'inv'));
    }

    public function localView($key, $val = '')
    {
        if (Cookie::has('config')) {
            $config = json_decode(request()->cookie('config'), true);
            cookie()->forget('config');
        } else {
            $config = [
                'sidebarColor' => 'success',
                'sidebarType' => 'bg-gradient-dark',
                'navbarFixed' => 'position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky',
                'sidebarMini' => 'g-sidenav-pinned',
                'darkMode' => '',
            ];
        }

        $config[$key] = $val;

        $cookie = cookie('config', json_encode($config), 60 * 24 * 365);
        Session::put('config', json_encode($config));

        return response()->json([
            'success' => true,
            'msg' => 'Pengaturan Halaman berhasil disimpan'
        ])->cookie($cookie);
    }
}

[
    'sidebar-color' => [
        'target' => '#sidenav-main',
        'attr' => 'data-color',
        'default-value' => 'success',
        'value' => ''
    ],
    'sidebar-tipe' => [
        'target' => '#sidenav-main',
        'attr' => 'class',
        'default-value' => 'bg-gradient-dark',
        'value' => ''
    ],
    'navbar-fixed' => [
        'target' => '#navbarBlur',
        'attr' => 'class',
        'default-value' => 'position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky',
        'value' => ''
    ],
    'sidebar-mini' => [
        'target' => 'body',
        'attr' => 'class',
        'default-value' => 'g-sidenav-pinned',
        'value' => 'g-sidenav-hidden'
    ],
    'sidebar-mini' => [
        'target' => 'body',
        'attr' => 'class',
        'default-value' => '',
        'value' => 'dark-version'
    ],
];
