<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\DataPemanfaat;
use App\Models\DokumenPinjaman;
use App\Models\JenisJasa;
use App\Models\JenisProdukPinjaman;
use App\Models\Kecamatan;
use App\Models\Kelompok;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\Rekening;
use App\Models\RencanaAngsuran;
use App\Models\SistemAngsuran;
use App\Models\StatusPinjaman;
use App\Models\Transaksi;
use App\Models\User;
use App\Utils\Keuangan;
use App\Utils\Pinjaman;
use App\Utils\Tanggal;
use DB;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DNS1D;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Session;

class PinjamanKelompokController extends Controller
{
    public function index()
    {
        if (in_array('tahapan_perguliran.proposal', Session::get('tombol'))) {
            $status = 'P';
        } elseif (in_array('tahapan_perguliran.verifikasi', Session::get('tombol'))) {
            $status = 'V';
        } elseif (in_array('tahapan_perguliran.waiting', Session::get('tombol'))) {
            $status = 'W';
        } elseif (in_array('tahapan_perguliran.aktif', Session::get('tombol'))) {
            $status = 'A';
        } elseif (in_array('tahapan_perguliran.lunas', Session::get('tombol'))) {
            $status = 'L';
        }

        if (request()->get('status')) {
            $status = request()->get('status');
        }

        $status = strtolower($status);

        $title = 'Tahapan Perguliran';
        return view('perguliran.index')->with(compact('title', 'status'));
    }

    public function proposal()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where([
                ['status', 'P']
            ])->with('kelompok', 'kelompok.d', 'jpp', 'sts', 'pinjaman_anggota')->get();

            return DataTables::of($pinkel)
                ->addColumn('jasa', function ($row) {
                    $jangka = $row->jangka;
                    $pros = $row->pros_jasa;

                    $jasa = number_format($pros / $jangka, 2);
                    return $jasa . '% / ' . $jangka . ' bln';
                })
                ->editColumn('nama_kelompok', function ($row) {
                    $jpp = $row->jpp;
                    $status = $row->sts->warna_status;

                    $nama_kelompok = $row->kelompok->nama_kelompok . ' (' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                // ->editColumn('tgl_proposal', function ($row) {
                //     return Tanggal::tglIndo($row->tgl_proposal);
                // })
                ->editColumn('proposal', function ($row) {
                    return number_format(intval($row->proposal));
                })
                ->editColumn('kelompok.alamat_kelompok', function ($row) {
                    return $row->kelompok->alamat_kelompok . ' ' . $row->kelompok->d->nama_desa;
                })
                ->addColumn('pinjaman_anggota_count', function ($row) {
                    return count($row->pinjaman_anggota);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function verified()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where([
                ['status', 'V']
            ])->with('kelompok', 'kelompok.d', 'jpp', 'sts', 'pinjaman_anggota')->get();

            return DataTables::of($pinkel)
                ->addColumn('jasa', function ($row) {
                    $jangka = $row->jangka;
                    $pros = $row->pros_jasa;

                    $jasa = number_format($pros / $jangka, 2);
                    return $jasa . '% / ' . $jangka . ' bln';
                })
                ->editColumn('nama_kelompok', function ($row) {
                    $jpp = $row->jpp;
                    $status = $row->sts->warna_status;

                    $nama_kelompok = $row->kelompok->nama_kelompok . ' (' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                // ->editColumn('tgl_verifikasi', function ($row) {
                //     return Tanggal::tglIndo($row->tgl_verifikasi);
                // })
                ->editColumn('verifikasi', function ($row) {
                    return number_format($row->verifikasi);
                })
                ->editColumn('kelompok.alamat_kelompok', function ($row) {
                    return $row->kelompok->alamat_kelompok . ' ' . $row->kelompok->d->nama_desa;
                })
                ->addColumn('pinjaman_anggota_count', function ($row) {
                    return count($row->pinjaman_anggota);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function waiting()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where([
                ['status', 'W']
            ])->with('kelompok', 'kelompok.d', 'jpp', 'sts', 'pinjaman_anggota')->get();

            return DataTables::of($pinkel)
                ->addColumn('jasa', function ($row) {
                    $jangka = $row->jangka;
                    $pros = $row->pros_jasa;

                    $jasa = number_format($pros / $jangka, 2);
                    return $jasa . '% / ' . $jangka . ' bln';
                })
                ->editColumn('nama_kelompok', function ($row) {
                    $jpp = $row->jpp;
                    $status = $row->sts->warna_status;

                    $nama_kelompok = $row->kelompok->nama_kelompok . ' (' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                // ->editColumn('tgl_tunggu', function ($row) {
                //     return Tanggal::tglIndo($row->tgl_tunggu);
                // })
                ->editColumn('alokasi', function ($row) {
                    return number_format($row->alokasi);
                })
                ->editColumn('kelompok.alamat_kelompok', function ($row) {
                    return $row->kelompok->alamat_kelompok . ' ' . $row->kelompok->d->nama_desa;
                })
                ->addColumn('pinjaman_anggota_count', function ($row) {
                    return count($row->pinjaman_anggota);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function aktif()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where([
                ['status', 'A']
            ])->with('kelompok', 'kelompok.d', 'jpp', 'sts', 'pinjaman_anggota')->get();

            return DataTables::of($pinkel)
                ->addColumn('jasa', function ($row) {
                    $jangka = $row->jangka;
                    $pros = $row->pros_jasa;

                    $jasa = number_format($pros / $jangka, 2);
                    return $jasa . '% / ' . $jangka . ' bln';
                })
                ->editColumn('nama_kelompok', function ($row) {
                    $jpp = $row->jpp;
                    $status = $row->sts->warna_status;

                    $nama_kelompok = $row->kelompok->nama_kelompok . ' (' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                // ->editColumn('tgl_cair', function ($row) {
                //     return Tanggal::tglIndo($row->tgl_cair);
                // })
                ->editColumn('alokasi', function ($row) {
                    return number_format($row->alokasi);
                })
                ->editColumn('kelompok.alamat_kelompok', function ($row) {
                    return $row->kelompok->alamat_kelompok . ' ' . $row->kelompok->d->nama_desa;
                })
                ->addColumn('pinjaman_anggota_count', function ($row) {
                    return count($row->pinjaman_anggota);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function lunas()
    {
        if (request()->ajax()) {
            $tb_pinkel = 'pinjaman_kelompok_' . Session::get('lokasi');
            $pinkel = PinjamanKelompok::where([
                ['status', 'A']
            ])->whereRaw($tb_pinkel . '.alokasi<=(SELECT SUM(realisasi_pokok) FROM real_angsuran_' . Session::get('lokasi') . ' WHERE loan_id=' . $tb_pinkel . '.id)')
                ->with('kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();

            return DataTables::of($pinkel)
                ->addColumn('jasa', function ($row) {
                    $jangka = $row->jangka;
                    $pros = $row->pros_jasa;

                    $jasa = number_format($pros / $jangka, 2);
                    return $jasa . '% / ' . $jangka . ' bln';
                })
                ->editColumn('nama_kelompok', function ($row) {
                    $jpp = $row->jpp;
                    $status = $row->sts->warna_status;

                    $nama_kelompok = $row->kelompok->nama_kelompok . ' (' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                // ->editColumn('tgl_cair', function ($row) {
                //     return Tanggal::tglIndo($row->tgl_cair);
                // })
                ->editColumn('alokasi', function ($row) {
                    return number_format($row->alokasi);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id_kel = request()->get('id_kel');
        $title = 'Registrasi Pinjaman';
        return view('pinjaman.create')->with(compact('title', 'id_kel'));
    }

    public function DaftarKelompok()
    {
        $id_kel = request()->get('id_kel') ?: 0;
        $kelompok = Kelompok::with([
            'd',
            'pinjaman' => function ($query) {
                $query->orderBy('tgl_proposal', 'DESC');
            }
        ])->withCount('pinjaman')->orderBy('nama_kelompok', 'ASC')->get();

        return view('pinjaman.partials.kelompok')->with(compact('kelompok', 'id_kel'));
    }

    public function register($id_kel)
    {
        $kelompok = Kelompok::where('id', $id_kel)->with([
            'pinjaman' => function ($query) {
                $query->orderBy('tgl_proposal', 'DESC');
            },
            'pinjaman.sts'
        ])->first();
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        $jenis_jasa = JenisJasa::all();
        $sistem_angsuran = SistemAngsuran::all();
        $jenis_pp = JenisProdukPinjaman::where('lokasi', '0')->orWhere('lokasi', Session::get('lokasi'))->get();

        $jenis_pp_dipilih = $kelompok->jenis_produk_pinjaman;

        if ($kelompok->pinjaman) {
            $status = $kelompok->pinjaman->status;
            if ($status == 'P' || $status == 'V' || $status == 'W') {
                return view('pinjaman.partials.pinjaman')->with(compact('kelompok', 'kec', 'jenis_jasa', 'sistem_angsuran', 'jenis_pp', 'jenis_pp_dipilih'));
            }
        }

        return view('pinjaman.partials.register')->with(compact('kelompok', 'kec', 'jenis_jasa', 'sistem_angsuran', 'jenis_pp', 'jenis_pp_dipilih'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kel = Kelompok::where('id', $request->id_kel)->first();

        $data_request = [
            'tgl_proposal',
            'pengajuan',
            'jangka',
            'pros_jasa',
            'jenis_jasa',
            'sistem_angsuran_pokok',
            'sistem_angsuran_jasa',
            'jenis_produk_pinjaman'
        ];

        $validasi = [
            'tgl_proposal' => 'required',
            'pengajuan' => 'required',
            'jangka' => 'required',
            'pros_jasa' => 'required',
            'jenis_jasa' => 'required',
            'sistem_angsuran_pokok' => 'required',
            'sistem_angsuran_jasa' => 'required',
            'jenis_produk_pinjaman' => 'required',
        ];

        if ($request->jenis_produk_pinjaman == '3') {
            array_push($data_request, 'pimpinan', 'penanggung_jawab');

            $validasi['pimpinan'] = 'required';
            $validasi['penanggung_jawab'] = 'required';

            $struktur_kelompok = [
                'ketua' => $request->pimpinan,
                'sekretaris' => $request->penanggung_jawab,
            ];
        } else {
            array_push($data_request, 'ketua', 'sekretaris', 'bendahara');

            $validasi['ketua'] = 'required';
            $validasi['sekretaris'] = 'required';
            $validasi['bendahara'] = 'required';

            $struktur_kelompok = [
                'ketua' => $request->ketua,
                'sekretaris' => $request->sekretaris,
                'bendahara' => $request->bendahara,
            ];
        }

        $data = $request->only($data_request);
        $validate = Validator::make($data, $validasi);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $pinjaman_ke = PinjamanKelompok::where([
            ['id_kel', $request->id_kel],
            ['tgl_proposal', '<=', Tanggal::tglNasional($request->tgl_proposal)]
        ])->count();

        $insert = [
            'pinjaman_ke' => $pinjaman_ke + 1,
            'id_kel' => $request->id_kel,
            'jenis_pp' => $request->jenis_produk_pinjaman,
            'tgl_proposal' => Tanggal::tglNasional($request->tgl_proposal),
            'tgl_verifikasi' => Tanggal::tglNasional($request->tgl_proposal),
            'tgl_dana' => Tanggal::tglNasional($request->tgl_proposal),
            'tgl_tunggu' => Tanggal::tglNasional($request->tgl_proposal),
            'tgl_cair' => Tanggal::tglNasional($request->tgl_proposal),
            'tgl_lunas' => Tanggal::tglNasional($request->tgl_proposal),
            'proposal' => str_replace(',', '', str_replace('.00', '', $request->pengajuan)),
            'verifikasi' => str_replace(',', '', str_replace('.00', '', $request->pengajuan)),
            'alokasi' => str_replace(',', '', str_replace('.00', '', $request->pengajuan)),
            'spk_no' => '0',
            'sumber' => '1',
            'pros_jasa' => $request->pros_jasa,
            'jenis_jasa' => $request->jenis_jasa,
            'jangka' => $request->jangka,
            'sistem_angsuran' => $request->sistem_angsuran_pokok,
            'sa_jasa' => $request->sistem_angsuran_jasa,
            'status' => 'P',
            'catatan_verifikasi' => '0',
            'struktur_kelompok' => json_encode($struktur_kelompok),
            'wt_cair' => '0',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ];

        $pinjaman_kelompok = PinjamanKelompok::create($insert);

        $jenis_pp = 'Piutang Kelompok';
        if ($insert['jenis_pp'] == '3') {
            $jenis_pp = 'Piutang Lembaga Lain';
        }
        return response()->json([
            'msg' => 'Proposal ' . $jenis_pp . ' ' . $kel->nama_kelompok . ' berhasil dibuat',
            'kode_kelompok' => $kel->kd_kelompok + 1,
            'desa' => $kel->desa,
            'id' => $pinjaman_kelompok->id
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Display the specified resource.
     */
    public function show(PinjamanKelompok $perguliran)
    {
        $perguliran = $perguliran->with([
            'sis_pokok',
            'sis_jasa',
            'jpp',
            'jasa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.pemanfaat' => function ($query) {
                $query->where([
                    ['status', 'A'],
                    ['lokasi', '!=', Session::get('lokasi')]
                ]);
            },
            'pinjaman_anggota.anggota.pemanfaat.kec',
            'pinjaman_anggota.pinjaman' => function ($query) {
                $query->where('status', 'A');
            },
            'pinjaman_anggota.pinjaman.pinkel',
            'pinjaman_anggota.pinjaman.kelompok',
            'pinjaman_anggota.pinjaman.anggota',
            'real',
            'real.transaksi'
        ])->where('id', $perguliran->id)->first();
        $jenis_jasa = JenisJasa::all();
        $sistem_angsuran = SistemAngsuran::all();
        $sumber_bayar = Rekening::where([
            ['lev1', '1'],
            ['lev2', '1'],
            ['lev3', '1'],
            ['lev4', '!=', '2']
        ])->orderBy('kode_akun', 'asc')->get();
        $debet = '1.1.03.' . str_pad($perguliran->jenis_pp, 2, '0', STR_PAD_LEFT);

        if ($perguliran->status == 'A' || $perguliran->status == 'L' || $perguliran->status == 'R' || $perguliran->status == 'H' || $perguliran->status == 'T') {
            $view = 'aktif';
        } elseif ($perguliran->status == 'W') {
            $view = 'waiting';
        } elseif ($perguliran->status == 'V') {
            $view = 'verifikasi';
        } elseif ($perguliran->status == 'P') {
            $view = 'proposal';
        } elseif ($perguliran->status == '0') {
            $view = 'edit_proposal';
        }

        $pinj_a = [];
        if ($perguliran->status == 'W') {
            $pinkel_aktif = PinjamanKelompok::where([['id_kel', $perguliran->id_kel], ['status', 'A']]);

            $pinjaman_anggota = $perguliran->pinjaman_anggota;
            $pinj_a['jumlah_pinjaman'] = 0;
            $pinj_a['jumlah_pemanfaat'] = 0;
            $pinj_a['jumlah_kelompok'] = 0;

            if ($perguliran->jenis_pp != '3') {
                foreach ($pinjaman_anggota as $pa) {
                    $pinj_aktif = $pa->pinjaman;

                    if ($pinj_aktif) {
                        $pinj_a['jumlah_pinjaman'] += 1;
                        $pinj_a['pinjaman'][] = $pinj_aktif;
                    }

                    if ($pa->anggota) {
                        $pemanfaat_aktif = $pa->anggota->pemanfaat;
                        if ($pemanfaat_aktif) {
                            $pinj_a['jumlah_pemanfaat'] += 1;
                            $pinj_a['pemanfaat'][$pa->anggota->nik] = $pemanfaat_aktif;
                        }
                    }
                }

                $pinjaman_kelompok = PinjamanKelompok::where('id_kel', $perguliran->id_kel)->where('status', 'A')->with('kelompok')->get();
                foreach ($pinjaman_kelompok as $pinkel) {
                    $pinj_a['jumlah_kelompok'] += 1;
                    $pinj_a['kelompok'][] = $pinkel;
                }
            }
        }

        return view('perguliran.partials/' . $view)->with(compact('perguliran', 'jenis_jasa', 'sistem_angsuran', 'sumber_bayar', 'debet', 'pinj_a'));
    }

    public function detail(PinjamanKelompok $perguliran)
    {
        $perguliran = $perguliran->with([
            'sis_pokok',
            'sis_jasa',
            'jpp',
            'jasa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.pemanfaat' => function ($query) {
                $query->where([
                    ['status', 'A'],
                    ['lokasi', '!=', Session::get('lokasi')]
                ]);
            },
            'pinjaman_anggota.anggota.pemanfaat.kec',
            'pinjaman_anggota.pinjaman' => function ($query) {
                $query->where('status', 'A');
            },
            'pinjaman_anggota.pinjaman.pinkel',
            'pinjaman_anggota.pinjaman.kelompok',
            'pinjaman_anggota.pinjaman.anggota',
        ])->where('id', $perguliran->id)->first();

        $title = 'Detal Piutang Kelompok ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $sistem_angsuran = SistemAngsuran::all();

        $dokumenPinjaman = DokumenPinjaman::orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->get();

        $pinkel_aktif = PinjamanKelompok::where([['id_kel', $perguliran->id_kel], ['status', 'A']]);

        $pinjaman_anggota = $perguliran->pinjaman_anggota;
        $pinj_a['jumlah_pinjaman'] = 0;
        $pinj_a['jumlah_pemanfaat'] = 0;
        $pinj_a['jumlah_kelompok'] = 0;

        foreach ($pinjaman_anggota as $pa) {
            $pinj_aktif = $pa->pinjaman;

            if ($pinj_aktif) {
                $pinj_a['jumlah_pinjaman'] += 1;
                $pinj_a['pinjaman'][] = $pinj_aktif;
            }

            if ($pa->anggota) {
                $pemanfaat_aktif = $pa->anggota->pemanfaat;
                if ($pemanfaat_aktif) {
                    $pinj_a['jumlah_pemanfaat'] += 1;
                    $pinj_a['pemanfaat'][$pa->anggota->nik] = $pemanfaat_aktif;
                }
            }
        }

        $pinjaman_kelompok = PinjamanKelompok::where('id_kel', $perguliran->id_kel)->where('status', 'A')->with('kelompok')->get();
        foreach ($pinjaman_kelompok as $pinkel) {
            $pinj_a['jumlah_kelompok'] += 1;
            $pinj_a['kelompok'][] = $pinkel;
        }

        return view('perguliran.detail')->with(compact('title', 'dokumenPinjaman', 'perguliran', 'real', 'sistem_angsuran', 'pinj_a'));
    }

    public function pelunasan(PinjamanKelompok $perguliran)
    {
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        $title = 'Detal Piutang Kelompok ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $ra = RencanaAngsuran::where('loan_id', $perguliran->id)->orderBy('jatuh_tempo', 'DESC')->first();
        return view('perguliran.partials.lunas')->with(compact('title', 'perguliran', 'real', 'ra', 'kec'));
    }

    public function keterangan(PinjamanKelompok $perguliran)
    {
        $title = 'Cetak Keterangan Pelunasan ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $ra = RencanaAngsuran::where('loan_id', $perguliran->id)->orderBy('jatuh_tempo', 'DESC')->first();
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        $dir = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        return view('perguliran.partials.cetak_keterangan')->with(compact('title', 'perguliran', 'real', 'ra', 'kec', 'dir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PinjamanKelompok $perguliran)
    {
        $jenis_jasa = JenisJasa::all();
        $sistem_angsuran = SistemAngsuran::all();
        $jenis_pp = JenisProdukPinjaman::where('lokasi', '0')->get();

        $jenis_jasa_dipilih = $perguliran->jenis_jasa;
        $sistem_angsuran_pokok = $perguliran->sistem_angsuran;
        $sistem_angsuran_jasa = $perguliran->sa_jasa;
        $jenis_pp_dipilih = $perguliran->jenis_pp;

        return view('perguliran.partials.edit_proposal')->with(compact('perguliran', 'jenis_jasa', 'sistem_angsuran', 'jenis_pp', 'jenis_jasa_dipilih', 'sistem_angsuran_pokok', 'sistem_angsuran_jasa', 'jenis_pp_dipilih'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PinjamanKelompok $perguliran)
    {
        $keuangan = new Keuangan;
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        if ($request->status == 'P') {
            $tgl = 'tgl_proposal';
            $alokasi = 'proposal';
        } elseif ($request->status == 'V') {
            $tgl = 'tgl_verifikasi';
            $alokasi = 'verifikasi';
        } elseif ($request->status == 'W') {
            $tgl = 'tgl_tunggu';
            $alokasi = 'alokasi';
        } elseif ($request->status == 'A') {
            $tgl = 'tgl_cair';
            $alokasi = 'alokasi';
        }

        if ($request->status == 'L') {
            PinjamanAnggota::where('id_pinkel', $perguliran->id)->update([
                'status' => 'L',
                'tgl_lunas' => date('Y-m-d')
            ]);

            DataPemanfaat::where('id_pinkel', $perguliran->id)->where('lokasi', Session::get('lokasi'))->update([
                'status' => 'L'
            ]);

            PinjamanKelompok::where('id', $perguliran->id)->update([
                'status' => 'L',
                'tgl_lunas' => date('Y-m-d')
            ]);

            return response()->json([
                'msg' => 'Validasi Pelunasan Piutang Kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil.',
                'id' => $perguliran->id
            ], Response::HTTP_ACCEPTED);
        }

        if ($request->status == 'P') {
            $data = $request->only([
                '_id',
                'status',
                $tgl,
                $alokasi,
                'jangka_proposal',
                'pros_jasa_proposal',
                'jenis_jasa_proposal',
                'sistem_angsuran_pokok_proposal',
                'sistem_angsuran_jasa_proposal',
                'ketua',
                'sekretaris',
                'bendahara'
            ]);

            $validate = Validator::make($data, [
                $tgl => 'required',
                $alokasi => 'required',
                'jangka_proposal' => 'required',
                'pros_jasa_proposal' => 'required',
                'jenis_jasa_proposal' => 'required',
                'sistem_angsuran_pokok_proposal' => 'required',
                'sistem_angsuran_jasa_proposal' => 'required',
                'ketua' => 'required',
                'sekretaris' => 'required',
                'bendahara' => 'required',
            ]);

            $data['jangka'] = $data['jangka_proposal'];
            $data['pros_jasa'] = $data['pros_jasa_proposal'];
            $data['jenis_jasa'] = $data['jenis_jasa_proposal'];
            $data['sistem_angsuran_pokok'] = $data['sistem_angsuran_pokok_proposal'];
            $data['sistem_angsuran_jasa'] = $data['sistem_angsuran_jasa_proposal'];
        } elseif ($request->status == 'W') {
            $data = $request->only([
                '_id',
                'status',
                $tgl,
                $alokasi,
                'jangka',
                'pros_jasa',
                'jenis_jasa',
                'sistem_angsuran_pokok',
                'sistem_angsuran_jasa',
                'tgl_cair',
                'nomor_spk'
            ]);

            $table = 'pinjaman_kelompok_' . Session::get('lokasi');
            $validate = [
                $tgl => 'required',
                $alokasi => 'required',
                'jangka' => 'required',
                'pros_jasa' => 'required',
                'jenis_jasa' => 'required',
                'sistem_angsuran_pokok' => 'required',
                'sistem_angsuran_jasa' => 'required',
                'tgl_cair' => 'required'
            ];

            $validate = Validator::make($data, $validate);
        } elseif ($request->status == 'A') {
            $data = $request->only([
                '_id',
                'status',
                $tgl,
                $alokasi,
                'sumber_pembayaran',
                'debet'
            ]);

            $validate = Validator::make($data, [
                $tgl => 'required',
                $alokasi => 'required',
                'sumber_pembayaran' => 'required'
            ]);
        } else {
            $data = $request->only([
                '_id',
                'status',
                $tgl,
                $alokasi,
                'jangka',
                'pros_jasa',
                'jenis_jasa',
                'sistem_angsuran_pokok',
                'sistem_angsuran_jasa',
                'catatan_verifikasi'
            ]);

            $validate = Validator::make($data, [
                $tgl => 'required',
                $alokasi => 'required',
                'jangka' => 'required',
                'pros_jasa' => 'required',
                'jenis_jasa' => 'required',
                'sistem_angsuran_pokok' => 'required',
                'sistem_angsuran_jasa' => 'required',
                'catatan_verifikasi' => 'required'
            ]);
        }

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        if ($data['status'] == '0') {
            $data['status'] = 'P';
        }

        if ($request->status == 'A') {
            if (strtotime(Tanggal::tglNasional($data[$tgl])) < strtotime($kec->tgl_pakai)) {

                return response()->json([
                    'success' => false,
                    'msg' => 'Tanggal pencairan tidak boleh sebelum tanggal pakai aplikasi.',
                ], Response::HTTP_ACCEPTED);
            }

            $tgl_cair = Tanggal::tglNasional($data[$tgl]);
            $sumber_dana = $data['sumber_pembayaran'];

            $data['tahun'] = Tanggal::tahun($tgl_cair);
            $data['bulan'] = Tanggal::bulan($tgl_cair);

            $rek = Rekening::where('kode_akun', $sumber_dana)->with([
                'kom_saldo' => function ($query) use ($data) {
                    $query->where('tahun', $data['tahun'])->where(function ($query) use ($data) {
                        $query->where('bulan', '0')->orwhere('bulan', $data['bulan']);
                    });
                }
            ])->first();
            $saldo = $keuangan->komSaldo($rek);
            $alokasi_pencairan = str_replace(',', '', str_replace('.00', '', $data[$alokasi]));

            if (intval($alokasi_pencairan) > round($saldo)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Saldo tidak mencukupi untuk melakukan pencairan piutang.',
                ], Response::HTTP_ACCEPTED);
            }

            $update = [
                $tgl => Tanggal::tglNasional($data[$tgl]),
                $alokasi => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'status' => 'A'
            ];

            PinjamanAnggota::where('id_pinkel', $perguliran->id)->update([
                $tgl => Tanggal::tglNasional($data[$tgl]),
                'status' => 'A'
            ]);

            DataPemanfaat::where([['id_pinkel', $perguliran->id], ['lokasi', Session::get('lokasi')]])->update([
                'status' => 'A'
            ]);

            $desa = $perguliran->kelompok->d;
            $nama_desa = $desa->sebutan_desa->sebutan_desa . ' ' . $desa->nama_desa;

            $keterangan = 'Pencairan Kel. ' . $perguliran->kelompok->nama_kelompok . ' ' . $nama_desa;
            $keterangan .= ' (' . $perguliran->jpp->nama_jpp . ')';

            Transaksi::create([
                'tgl_transaksi' => (string) Tanggal::tglNasional($data[$tgl]),
                'rekening_debit' => (string) $request->debet,
                'rekening_kredit' => (string) $request->sumber_pembayaran,
                'idtp' => '0',
                'id_pinj' => $perguliran->id,
                'id_pinj_i' => '0',
                'keterangan_transaksi' => (string) $keterangan,
                'relasi' => (string) $perguliran->kelompok->nama_kelompok . " [" . $perguliran->id . "] " . $perguliran->kelompok->ketua,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'urutan' => '0',
                'id_user' => auth()->user()->id,
            ]);
        } elseif ($request->status == 'W') {
            if ($request->idpa != null) {

                $UpdatePinjamanAnggota = [];
                $UpdateDataPemanfaat = [];

                foreach ($request->idpa as $idpa => $val) {
                    $val = (int) str_replace([',', '.00'], '', $val);
                    $UpdatePinjamanAnggota[] = [
                        'id' => $idpa,
                        'tgl_dana' => Tanggal::tglNasional($data[$tgl]),
                        'tgl_cair' => Tanggal::tglNasional($data[$tgl]),
                        $tgl => Tanggal::tglNasional($data[$tgl]),
                        $alokasi => $val,
                        'status' => $data['status']
                    ];

                    $UpdateDataPemanfaat[] = [
                        'idpa' => $idpa,
                        'lokasi' => Session::get('lokasi'),
                        'status' => $data['status']
                    ];
                }

                $query = "UPDATE data_pemanfaat SET status = CASE ";
                $cases = [];
                $params = [];

                foreach ($UpdateDataPemanfaat as $update) {
                    $cases[] = "WHEN idpa = ? AND lokasi = ? THEN ?";
                    $params[] = $update['idpa'];
                    $params[] = $update['lokasi'];
                    $params[] = $update['status'];
                }

                $query .= implode(' ', $cases);
                $query .= " ELSE status END";

                PinjamanAnggota::upsert($UpdatePinjamanAnggota, ['id'], ['tgl_dana', 'tgl_cair', $tgl, $alokasi, 'status']);
                DB::update($query, $params);
            }

            if (!$request->nomor_spk) {
                $tgl_cair = Tanggal::tglNasional($data[$tgl]);
                $nomor_spk = $perguliran->id;
                $nomor_spk .= '/DS-' . substr($perguliran->kelompok->d->kode_desa, -2);
                $nomor_spk .= '/' .  Tanggal::tglRomawi($tgl_cair);

                $data['nomor_spk'] = $nomor_spk;
            }

            $update = [
                'tgl_dana' => Tanggal::tglNasional($data[$tgl]),
                'tgl_cair' => Tanggal::tglNasional($data[$tgl]),
                $tgl => Tanggal::tglNasional($data[$tgl]),
                $alokasi => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'jangka' => $data['jangka'],
                'pros_jasa' => $data['pros_jasa'],
                'jenis_jasa' => $data['jenis_jasa'],
                'sistem_angsuran' => $data['sistem_angsuran_pokok'],
                'sa_jasa' => $data['sistem_angsuran_jasa'],
                'tgl_cair' => Tanggal::tglNasional($data['tgl_cair']),
                'spk_no' => $data['nomor_spk'],
                'status' => $data['status']
            ];
        } else {
            if ($request->idpa != null) {
                $UpdatePinjamanAnggota = [];
                $UpdateDataPemanfaat = [];

                foreach ($request->idpa as $idpa => $val) {
                    $val = (int) str_replace([',', '.00'], '', $val);
                    $UpdatePinjamanAnggota[] = [
                        'id' => $idpa,
                        $tgl => Tanggal::tglNasional($data[$tgl]),
                        $alokasi => $val,
                        'status' => 'P',
                        'catatan_verifikasi' => $request->catatan[$idpa]
                    ];

                    $UpdateDataPemanfaat[] = [
                        'idpa' => $idpa,
                        'lokasi' => Session::get('lokasi'),
                        'status' => $data['status']
                    ];
                }

                $query = "UPDATE data_pemanfaat SET status = CASE ";
                $cases = [];
                $params = [];

                foreach ($UpdateDataPemanfaat as $update) {
                    $cases[] = "WHEN idpa = ? AND lokasi = ? THEN ?";
                    $params[] = $update['idpa'];
                    $params[] = $update['lokasi'];
                    $params[] = $update['status'];
                }

                $query .= implode(' ', $cases);
                $query .= " ELSE status END";

                PinjamanAnggota::upsert($UpdatePinjamanAnggota, ['id'], ['tgl_dana', 'tgl_cair', $tgl, $alokasi, 'status', 'catatan_verifikasi']);
                DB::update($query, $params);
            }

            $update = [
                $tgl => Tanggal::tglNasional($data[$tgl]),
                $alokasi => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'jangka' => $data['jangka'],
                'pros_jasa' => $data['pros_jasa'],
                'jenis_jasa' => $data['jenis_jasa'],
                'sistem_angsuran' => $data['sistem_angsuran_pokok'],
                'sa_jasa' => $data['sistem_angsuran_jasa'],
                'status' => $data['status']
            ];

            if ($request->status == 'P') {
                $update['jenis_pp'] = $request->jenis_produk_pinjaman;
                $update['struktur_kelompok'] = json_encode([
                    'ketua' => $request->ketua,
                    'sekretaris' => $request->sekretaris,
                    'bendahara' => $request->bendahara,
                ]);
            }

            if ($request->status == 'V') {
                $update['catatan_verifikasi'] = $data['catatan_verifikasi'];
            }
        }

        $pinjaman_ke = PinjamanKelompok::where([
            ['id_kel', $perguliran->id_kel],
            ['id', '!=', $perguliran->id],
            ['tgl_proposal', '<=', $perguliran->tgl_proposal]
        ])->count();
        $update['pinjaman_ke'] = $pinjaman_ke + 1;

        $pinkel = PinjamanKelompok::where('id', $perguliran->id)->update($update);

        if ($request->status == 'W' || $request->status == 'A') {
            $this->generate($perguliran->id);
        }

        if ($perguliran->status == 'P') {
            $msg = 'Rekom Verifikator berhasil disimpan';
            if ($request->status == 'P') {
                $msg = 'Proposal berhasil diedit';
            }
        } elseif ($perguliran->status == 'V') {
            $msg = 'Keputusan Pendanaan berhasil disimpan';
        } elseif ($perguliran->status == 'W') {
            $msg = 'Proposal Kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil dicairkan';
        } elseif ($perguliran->status == '0') {
            $msg = 'Proposal berhasil diedit';
        }

        return response()->json([
            'success' => true,
            'msg' => $msg,
            'id' => $perguliran->id
        ], Response::HTTP_ACCEPTED);
    }

    public function catatan(PinjamanKelompok $perguliran)
    {
        $catatan = collect(json_decode($perguliran->catatan_bimbingan, true));
        $data_catatan = $catatan->sortByDesc('tanggal')->values();

        $data_user = [];
        $user = $catatan->pluck('user')->unique();
        $users = User::whereIn('id', $user)->get();
        foreach ($users as $user) {
            $data_user[$user->id] = $user->namadepan . ' ' . $user->namabelakang;
        }

        return response()->json([
            'success' => true,
            'view' => view('perguliran.partials.catatan_bimbingan')->with(compact('data_catatan', 'data_user'))->render()
        ]);
    }

    public function deleteCatatan(Request $request, PinjamanKelompok $perguliran)
    {
        $catatan = collect(json_decode($perguliran->catatan_bimbingan, true));
        $data_catatan = $catatan->sortByDesc('tanggal')->values();

        $index = 1;
        $catatan_bimbingan = [];
        foreach ($data_catatan as $catatan) {
            if ($index != $request->index) {
                array_push($catatan_bimbingan, $catatan);
            }

            $index++;
        }

        $update = PinjamanKelompok::where('id', $perguliran->id)->update([
            'catatan_bimbingan' => json_encode($catatan_bimbingan)
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Catatan berhasil dihapus.'
        ]);
    }

    public function catatanBimbingan(Request $request, PinjamanKelompok $perguliran)
    {
        $data = $request->only([
            'tanggal_catatan',
            'catatan_bimbingan'
        ]);

        $validate = Validator::make($data, [
            'catatan_bimbingan' => 'required'
        ]);

        $data['catatan_bimbingan'] = str_replace("<br>", '', $data['catatan_bimbingan']);
        if ($validate->fails() || strlen($data['catatan_bimbingan']) < 8) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $catatan = json_decode($perguliran->catatan_bimbingan, true);
        if (!$catatan) {
            $catatan = [];
        }

        array_push($catatan, [
            'tanggal' => Tanggal::tglNasional($data['tanggal_catatan']),
            'catatan' => $data['catatan_bimbingan'],
            'user' => auth()->user()->id
        ]);

        $update = PinjamanKelompok::where('id', $perguliran->id)->update([
            'catatan_bimbingan' => json_encode($catatan)
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Catatan berhasil ditambahkan.'
        ]);
    }

    public function simpan(Request $request, $id)
    {
        $pinkel = PinjamanKelompok::where('id', $id)->with('kelompok')->first();

        $data = $request->only([
            'tglProposal',
            'tglVerifikasi',
            'waktuVerifikasi',
            'spk_no',
            'tgl_cair',
            'waktu',
            'tempat'
        ]);

        $jenis = $request->get('jenis');
        if ($jenis == 'dokumen_pencairan') {
            $wt_cair = $data['waktu'] . '_' . $data['tempat'];
            $updatePinjamanKelompok = [
                'spk_no' => $data['spk_no'],
                'tgl_cair' => Tanggal::tglNasional($data['tgl_cair']),
                'wt_cair' => $wt_cair
            ];

            $updatePinjamanAnggota = [
                'tgl_cair' => Tanggal::tglNasional($data['tgl_cair'])
            ];
        } else {
            $data['tgl_proposal'] = $data['tglProposal'];
            $data['tgl_verifikasi'] = $data['tglVerifikasi'];
            unset($data['tglProposal']);
            unset($data['tglVerifikasi']);

            $updatePinjamanKelompok = [
                'tgl_proposal' => Tanggal::tglNasional($data['tgl_proposal']),
                'tgl_verifikasi' => Tanggal::tglNasional($data['tgl_verifikasi']),
                'waktu_verifikasi' => $data['waktuVerifikasi']
            ];

            $updatePinjamanAnggota = [
                'tgl_proposal' => Tanggal::tglNasional($data['tgl_proposal']),
                'tgl_verifikasi' => Tanggal::tglNasional($data['tgl_verifikasi']),
            ];
        }

        PinjamanKelompok::where('id', $id)->update($updatePinjamanKelompok);
        PinjamanAnggota::where('id_pinkel', $id)->update($updatePinjamanAnggota);

        $this->generate($id);

        $data['success'] = true;
        $data['msg'] = 'Piutang Kelompok ' . $pinkel->kelompok->nama_kelompok . ' Berhasil Diperbarui';
        return response()->json($data);
    }

    public function tidakLayak(Request $request, PinjamanKelompok $id)
    {
        $today = date('Y-m-d');
        $update = [
            'tgl_dana' => $today,
            'tgl_tunggu' => $today,
            'tgl_cair' => $today,
            'tgl_lunas' => $today,
            'status' => 'T',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ];
        if ($id->status == 'V') {
            $update['tgl_verifikasi'] = $today;
        }

        PinjamanKelompok::where('id', $id->id)->update($update);
        PinjamanAnggota::where('id_pinkel', $id->id)->update($update);

        $this->generate($id->id);
        return response()->json([
            'success' => true,
            'msg' => 'Piutang Kelompok ' . $id->kelompok->nama_kelompok . ' Loan ID. ' . $id->id . ' berhasil diubah menjadi status T (Tidak Layak)',
            'id_pinkel' => $id->id
        ]);
    }

    public function kembaliProposal(Request $request, PinjamanKelompok $id)
    {
        $pinkel = PinjamanKelompok::where('id', $id->id)->update([
            'status' => 'P'
        ]);

        $pinjaman = PinjamanAnggota::where('id_pinkel', $id->id)->update([
            'status' => 'P'
        ]);

        $pemanfaat = DataPemanfaat::where([
            ['id_pinkel', $id->id],
            ['lokasi', Session::get('lokasi')]
        ])->update([
            'status' => 'P'
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Piutang Kelompok ' . $id->kelompok->nama_kelompok . ' Loan ID. ' . $id->id . ' berhasil dikembalikan menjadi status P (Pengajuan/Proposal)',
            'id_pinkel' => $id->id
        ]);
    }

    public function rescedule(Request $request)
    {
        $id = $request->id;
        $tgl_resceduling = Tanggal::tglNasional($request->tgl_resceduling);
        $pengajuan = $request->_pengajuan;
        $sis_pokok = $request->sistem_angsuran_pokok;
        $sis_jasa = $request->sistem_angsuran_jasa;
        $jangka = $request->jangka;
        $pros_jasa = $request->pros_jasa;

        $last_idtp = Transaksi::where('idtp', '!=', '0')->max('idtp');
        $pinkel = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'sis_pokok',
            'sis_jasa',
            'pinjaman_anggota',
            'saldo' => function ($query) use ($id, $tgl_resceduling) {
                $query->where([
                    ['loan_id', $id],
                    ['tgl_transaksi', '<=', $tgl_resceduling]
                ]);
            },
            'target' => function ($query) use ($id, $tgl_resceduling) {
                $query->where([
                    ['loan_id', $id],
                    ['jatuh_tempo', '<=', $tgl_resceduling]
                ]);
            }
        ])->withCount('pinjaman_anggota')->first();

        $sum_pokok = 0;
        $sum_jasa = 0;
        if ($pinkel->saldo) {
            $sum_pokok = $pinkel->saldo->sum_pokok;
            $sum_jasa = $pinkel->saldo->sum_jasa;
        }

        $target_pokok = 0;
        $target_jasa = 0;
        if ($pinkel->target) {
            $target_pokok = $pinkel->target->target_pokok;
            $target_jasa = $pinkel->target->target_jasa;
        }

        $tunggakan_pokok = $target_pokok - $sum_pokok;
        if ($tunggakan_pokok < '0') $tunggakan_pokok = '0';

        $tunggakan_jasa = $target_jasa - $sum_jasa;
        if ($tunggakan_jasa < '0') $tunggakan_jasa = '0';

        $alokasi_pokok = intval($pinkel->alokasi);
        $alokasi_jasa = intval($pinkel->pros_jasa == 0 ? 0 : $pinkel->alokasi * ($pinkel->pros_jasa / 100));

        if ($pinkel->jenis_pp == '1') {
            $rekening_1 = '1.1.01.01';
            $rekening_2 = '1.1.03.01';
        } elseif ($pinkel->jenis_pp == '2') {
            $rekening_1 = '1.1.01.01';
            $rekening_2 = '1.1.03.02';
        } else {
            $rekening_1 = '1.1.01.01';
            $rekening_2 = '1.1.03.03';
        }

        $trx_resc = Transaksi::create([
            'tgl_transaksi' => (string) $tgl_resceduling,
            'rekening_debit' => (string) $rekening_1,
            'rekening_kredit' => (string) $rekening_2,
            'idtp' => $last_idtp + 1,
            'id_pinj' => $pinkel->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => (string) 'Angs. Resc. ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')',
            'relasi' => (string) $pinkel->kelompok->nama_kelompok,
            'jumlah' => $pengajuan,
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        $update_pinkel = PinjamanKelompok::where('id', $id)->update([
            'tgl_lunas' => $tgl_resceduling,
            'status' => 'R',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ]);

        $update_pinj_a = PinjamanAnggota::where([
            ['id_pinkel', $id],
            ['status', 'A']
        ])->update([
            'tgl_lunas' => $tgl_resceduling,
            'status' => 'R',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ]);

        $pinjaman = PinjamanKelompok::create([
            'id_kel' => $pinkel->id_kel,
            'jenis_pp' => $pinkel->jenis_pp,
            'tgl_proposal' => $tgl_resceduling,
            'tgl_verifikasi' => $tgl_resceduling,
            'tgl_dana' => $tgl_resceduling,
            'tgl_tunggu' => $tgl_resceduling,
            'tgl_cair' => $tgl_resceduling,
            'tgl_lunas' => $tgl_resceduling,
            'proposal' => $pengajuan,
            'verifikasi' => $pengajuan,
            'alokasi' => $pengajuan,
            'spk_no' => $request->get('spk'),
            'sumber' => '2',
            'jenis_jasa' => $pinkel->jenis_jasa,
            'jangka' => $jangka,
            'pros_jasa' => $pros_jasa,
            'sistem_angsuran' => $sis_pokok,
            'sa_jasa' => $sis_jasa,
            'status' => 'A',
            'catatan_verifikasi' => $pinkel->catatan_verifikasi,
            'wt_cair' => $pinkel->wt_cair,
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ]);

        $trx_cair = Transaksi::create([
            'tgl_transaksi' => (string) $tgl_resceduling,
            'rekening_debit' => (string) $rekening_2,
            'rekening_kredit' => (string) $rekening_1,
            'idtp' => '0',
            'id_pinj' => $pinjaman->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => (string) 'Pencairan Resc ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinjaman->id . ')',
            'relasi' => (string) $pinkel->kelompok->nama_kelompok,
            'jumlah' => $pengajuan,
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        $realisasi_pokok = $trx_resc['jumlah'];
        $realisasi_jasa = 0;

        $sum_pokok += $realisasi_pokok;
        $alokasi_pokok -= $sum_pokok;
        $tunggakan_pokok -= $realisasi_pokok;
        if ($tunggakan_pokok <= 0) $tunggakan_pokok = 0;

        $sum_jasa += $realisasi_jasa;
        $alokasi_jasa -= $sum_jasa;
        $tunggakan_jasa -= $realisasi_jasa;
        if ($tunggakan_jasa <= 0) $tunggakan_jasa = 0;

        $real_angsuran = [
            'id' => $trx_resc['idtp'],
            'loan_id' => $pinkel->id,
            'tgl_transaksi' => $tgl_resceduling,
            'realisasi_pokok' => $realisasi_pokok,
            'realisasi_jasa' => $realisasi_jasa,
            'sum_pokok' => $sum_pokok,
            'sum_jasa' => $sum_jasa,
            'saldo_pokok' => $alokasi_pokok,
            'saldo_jasa' => $alokasi_jasa,
            'tunggakan_pokok' => $tunggakan_pokok,
            'tunggakan_jasa' => $tunggakan_jasa,
            'lu' => date('Y-m-d H:i:s', strtotime($tgl_resceduling)),
            'id_user' => auth()->user()->id,
        ];

        RealAngsuran::insert($real_angsuran);

        return response()->json([
            'success' => true,
            'status' => 'A',
            'id' => $pinjaman->id
        ]);
    }

    public function hapus(Request $request)
    {
        $last_idtp = Transaksi::where('idtp', '!=', '0')->max('idtp');
        $data = $request->only([
            'id',
            'saldo',
            'tgl_penghapusan',
            'alasan_penghapusan'
        ]);

        $pinkel = PinjamanKelompok::where('id', $data['id'])->with([
            'saldo',
            'target',
            'kelompok'
        ])->withCount('real')->firstOrFail();

        $tunggakan_pokok = 0;
        $tunggakan_jasa = 0;
        if ($pinkel->real_count > 0) {
            $pokok = $data['saldo'];
            $jasa = $pinkel->saldo->saldo_jasa;
            $sum_pokok = $pinkel->saldo->sum_pokok + $pokok;
            $sum_jasa = $pinkel->saldo->sum_jasa + $jasa;
            $saldo_pokok = $pinkel->saldo->saldo_pokok - $pokok;
            $saldo_jasa = $pinkel->saldo->saldo_jasa - $jasa;
        } else {
            $pokok = $data['saldo'];
            $jasa = $pinkel->target->target_jasa;
            $sum_pokok = $pokok;
            $sum_jasa = $jasa;
            $saldo_pokok = $pinkel->target->saldo_pokok - $pokok;
            $saldo_jasa = $pinkel->target->saldo_jasa - $jasa;
        }

        if ($pinkel->jenis_pp == '1') {
            $rekening_debit = '1.1.04.01';
            $rekening_kredit = '1.1.03.01';
        } elseif ($pinkel->jenis_pp == '2') {
            $rekening_debit = '1.1.04.02';
            $rekening_kredit = '1.1.03.02';
        } else {
            $rekening_debit = '1.1.04.03';
            $rekening_kredit = '1.1.03.03';
        }

        $pinj_kelompok = PinjamanKelompok::where('id', $pinkel->id)->update([
            'tgl_lunas' => Tanggal::tglNasional($data['tgl_penghapusan']),
            'catatan_verifikasi' => $data['alasan_penghapusan'],
            'status' => 'H'
        ]);

        $pinj_anggota = PinjamanAnggota::where('id_pinkel', $pinkel->id)->update([
            'tgl_lunas' => Tanggal::tglNasional($data['tgl_penghapusan']),
            'catatan_verifikasi' => $data['alasan_penghapusan'],
            'status' => 'H'
        ]);

        $trx = Transaksi::create([
            'tgl_transaksi' => (string) Tanggal::tglNasional($data['tgl_penghapusan']),
            'rekening_debit' => (string) $rekening_debit,
            'rekening_kredit' => (string) $rekening_kredit,
            'idtp' => $last_idtp + 1,
            'id_pinj' => $pinkel->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => (string) 'Penghapusan Piutang Kelompok ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')',
            'relasi' => (string) $pinkel->kelompok->nama_kelompok,
            'jumlah' => $data['saldo'],
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Penghapusan Piutang Kelompok ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ') berhasil',
            'id' => $pinkel->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PinjamanKelompok $perguliran)
    {
        if ($perguliran->status == 'P') {
            PinjamanAnggota::where('id_pinkel', $perguliran->id)->delete();

            PinjamanKelompok::destroy($perguliran->id);
            DataPemanfaat::where([
                'lokasi' => Session::get('lokasi'),
                'id_pinkel' => $perguliran->id
            ])->delete();

            return response()->json([
                'hapus' => true,
                'msg' => 'Proposal piutang kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil dihapus'
            ]);
        }

        return response()->json([
            'hapus' => false,
            'msg' => 'Proposal piutang kelompok ' . $perguliran->kelompok->nama_kelompok . ' gagal dihapus'
        ]);
    }

    public function cariKelompok()
    {
        $param = request()->get('query');
        if (strlen($param) >= '0') {
            $kelompok = Kelompok::leftJoin('desa', 'desa.kd_desa', '=', 'kelompok_' . Session::get('lokasi') . '.desa')
                ->leftJoin('pinjaman_kelompok_' . Session::get('lokasi') . ' as pk', 'pk.id_kel', '=', 'kelompok_' . Session::get('lokasi') . '.id')
                ->where(function ($query) use ($param) {
                    $query->where('kelompok_' . Session::get('lokasi') . '.nama_kelompok', 'like', '%' . $param . '%')
                        ->orwhere('kelompok_' . Session::get('lokasi') . '.kd_kelompok', 'like', '%' . $param . '%')
                        ->orwhere('kelompok_' . Session::get('lokasi') . '.ketua', 'like', '%' . $param . '%');
                })
                ->where('pk.status', 'A')
                ->get();

            return response()->json($kelompok);
        }

        return response()->json($param);
    }

    public function dokumen(Request $request)
    {
        $data['tahun'] = date('Y');
        $data['bulan'] = date('m');
        $data['hari'] = date('d');
        $data['type'] = 'pdf';

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten', 'kabupaten.wilayah', 'desa', 'ttd')->first();
        $kab = $kec->kabupaten;
        $dir = User::where([
            ['lokasi', Session::get('lokasi')],
            ['jabatan', '1'],
            ['level', '1'],
            ['sejak', '<=', date('Y-m-t', strtotime($data['tahun'] . '-' . $data['bulan'] . '-01'))]
        ])->first();

        $data['logo'] = $kec->logo;
        $data['nama_lembaga'] = $kec->nama_lembaga_sort;
        $data['nama_kecamatan'] = $kec->sebutan_kec . ' ' . $kec->nama_kec;

        if (Keuangan::startWith($kab->nama_kab, 'KOTA') || Keuangan::startWith($kab->nama_kab, 'KAB')) {
            $data['nama_kecamatan'] .= ' ' . ucwords(strtolower($kab->nama_kab));
            $data['nama_kabupaten'] = ucwords(strtolower($kab->nama_kab));
            $data['kabupaten'] = ucwords(strtolower($kab->nama_kab));
            $data['nama_kab'] = ucwords(strtolower($kab->nama_kab));
        } else {
            $data['nama_kecamatan'] .= ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
            $data['nama_kabupaten'] = ' Kabupaten ' . ucwords(strtolower($kab->nama_kab));
            $data['kabupaten'] = ' Kab. ' . ucwords(strtolower($kab->nama_kab));
            $data['nama_kab'] = ucwords(strtolower($kab->nama_kab));
        }

        $data['nomor_usaha'] = 'SK Kemenkumham RI No.' . $kec->nomor_bh;
        $data['info'] = $kec->alamat_kec . ', Telp.' . $kec->telpon_kec;
        $data['email'] = $kec->email_kec;
        $data['kec'] = $kec;
        $data['kab'] = $kab;
        $data['dir'] = $dir;

        if (strlen($data['hari']) > 0 && strlen($data['bulan']) > 0) {
            $data['tgl_kondisi'] = $data['tahun'] . '-' . $data['bulan'] . '-' . $data['hari'];
        } elseif (strlen($data['bulan']) > 0) {
            $data['tgl_kondisi'] = $data['tahun'] . '-' . $data['bulan'] . '-' . date('t', strtotime($data['tahun'] . '-' . $data['bulan']));
        } else {
            $data['tgl_kondisi'] = $data['tahun'] . '-12-31';
        }

        $report = explode('#', $request->report);
        $file = $report[0];

        $data['report'] = $file;
        $data['type'] = $report[1];
        $data['jenis_laporan'] = 'dokumen_pinjaman';

        $data['version'] = 'v1';
        if ($file == 'kartuAngsuranAnggota') {
            return $this->$file($request->id);
        }
        return $this->$file($request->id, $data);
    }

    public function coverProposal($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['judul'] = 'DOKUMEN PROPOSAL';
        $view = view('perguliran.dokumen.cover_proposal', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function check($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d'
        ])->first();

        $data['judul'] = 'Check List (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.check', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratPengajuanPinjaman($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Surat Pengajuan Kredit (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pengajuan_kredit', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratRekomendasi($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Rekomendasi Kredit (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.rekomendasi_kredit', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function profilKelompok($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.tk',
            'kelompok.usaha',
            'kelompok.kegiatan',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['judul'] = 'Profil Kelompok (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.profil_kelompok', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function susunanPengurus($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->first();

        $data['judul'] = 'Susunan Pengurus (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pengurus', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function anggotaKelompok($id, $data)
    {
        $data['pinjaman'] = PinjamanAnggota::where('id_pinkel', $id)->with([
            'anggota',
            'anggota.d'
        ])->orderBy('id', 'ASC')->get();

        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with('kelompok')->first();
        $data['judul'] = 'Daftar Anggota (Loan ID. ' . $id . ')';
        $view = view('perguliran.dokumen.anggota', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function daftarPemanfaat($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'sis_pokok',
            'kelompok',
            'pinjaman_anggota' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'pinjaman_anggota.anggota.u'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['pinjaman_ke'] = PinjamanKelompok::where('id_kel', $data['pinkel']->kelompok->id)->where('tgl_proposal', '<', $data['pinkel']->tgl_proposal)->count();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', str_replace('V2', '', $data['report'])],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Daftar Pemanfaat (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pemanfaat', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function daftarPemanfaatV2($id, $data)
    {
        $data['version'] = 'v2';
        return $this->daftarPemanfaat($id, $data);
    }

    public function tanggungRenteng($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Pernyataan Tanggung Renteng (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanggung_renteng', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function fotoCopyKTP($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
        ])->first();

        $data['judul'] = 'FC KTP Pemanfaat dan Penjamin (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.ktp', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function pernyataanPeminjam($id, $data)
    {
        $keuangan = new Keuangan;

        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'jasa',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.d'
        ])->first();

        $data['keuangan'] = $keuangan;

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Pernyataan Peminjam (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pernyataan_peminjam', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function baMusyawarahDesa($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'sis_pokok'
        ])->withCount('pinjaman_anggota')->first();

        $data['judul'] = 'BA Musyawarah (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.ba_musyawarah', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function formVerifikasi($id, $data)
    {
        $keuangan = new Keuangan;

        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'jasa',
            'kelompok',
            'kelompok.d',
            'kelompok.usaha',
            'kelompok.kegiatan',
            'kelompok.tk',
            'kelompok.fk',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.pinj_ang' => function ($query) use ($data, $id) {
                $query->where([
                    ['id_pinkel', '!=', $id]
                ]);
            },
            'sis_pokok',
            'user'
        ])->first();

        $data['user'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '4']
        ])->with('j')->orderBy('id')->get();

        $data['keuangan'] = $keuangan;
        $data['statusDokumen'] = request()->get('status');

        $data['judul'] = 'Form Verifikasi (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.form_verifikasi', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function BaPendanaan($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->first();
        $data['pinjaman'] = PinjamanKelompok::where([
            ['tgl_cair', $data['pinkel']->tgl_cair],
            ['status', $data['pinkel']->status]
        ])->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->get();

        $data['pendanaan'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '5']
        ])->with('j')->get();

        $data['dir'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'BA Pendanaan ' . Tanggal::tglLatin($data['pinkel']->tgl_tunggu);
        $view = view('perguliran.dokumen.ba_pendanaan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function formVerifikasiAnggota($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.u',
        ])->first();

        $data['verifikator'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '4']
        ])->orderBy('id', 'ASC')->get();

        $data['judul'] = 'Form Verifikasi Anggota (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.form_verifikasi_anggota', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function daftarHadirVerifikasi($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Daftar Hadir Verifikasi (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.daftar_hadir_verifikasi', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function rencanaAngsuran($id, $data)
    {

        $keuangan = new Keuangan;

        if (request()->get('status') == 'A') {
            $data['rencana'] = RencanaAngsuran::where([
                ['loan_id', $id],
                ['angsuran_ke', '!=', '0']
            ])->orderBy('jatuh_tempo', 'ASC')->get();
        } else {
            $data['rencana'] = $this->generate($id)->getData()->rencana_angsuran;
        }

        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'sis_pokok',
            'jasa',
            'saldo_pinjaman',
            'pinjaman_anggota'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['keuangan'] = $keuangan;
        $data['judul'] = 'Rencana Angsuran (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.rencana_angsuran', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function rekeningKoran($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'sis_pokok',
            'jasa'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['transaksi'] = Transaksi::where('id_pinj', $id)->orderBy('tgl_transaksi', 'ASC')->with('user')->orderBy('idtp', 'ASC')->get();

        $data['judul'] = 'Rekening Koran (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.rekening_koran', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function iptw($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota' => function ($query) {
                $query->where('status', 'A')->orwhere('status', 'W')->orwhere('status', 'L');
            },
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Daftar Penerima IPTW (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.iptw', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function pesertaAsuransi($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jasa',
            'sis_pokok',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Daftar Peserta Asuransi (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.peserta_asuransi', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function coverPencairan($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['judul'] = 'DOKUMEN PENCAIRAN';
        $view = view('perguliran.dokumen.cover_pencairan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function spk($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'jasa',
            'sis_pokok',
            'sis_jasa',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['dir_utama'] = User::where([
            ['level', '2'],
            ['jabatan', '65'],
            ['lokasi', Session::get('lokasi')],
        ])->first();

        $data['keuangan'] = $keuangan;

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Surat Perjanjian Kredit (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.spk', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratKelayakan($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Kelayakan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.surat_kelayakan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratKuasa($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.d',
            'pinjaman_anggota.anggota.d.sebutan_desa',
        ])->withCount('pinjaman_anggota')->first();

        $data['judul'] = 'Surat Kuasa (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.surat_kuasa', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function BaPencairan($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'jasa',
            'kelompok',
            'kelompok.d',
            'kelompok.usaha',
            'kelompok.kegiatan',
            'kelompok.tk',
            'kelompok.fk',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'sis_pokok'
        ])->withCount('pinjaman_anggota')->first();

        $data['user'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '4']
        ])->with('j')->orderBy('id')->get();

        $data['keuangan'] = $keuangan;

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Berita Acara Pencairan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.ba_pencairan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function daftarHadirPencairan($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Daftar Hadir Pencairan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.daftar_hadir_pencairan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function tandaTerima($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'sis_pokok',
            'kelompok',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Tanda Terima (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanda_terima', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function kartuAngsuran($id)
    {
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'jpp',
            'sis_pokok',
            'real',
            'real.transaksi',
            'rencana' => function ($query) {
                $query->where('angsuran_ke', '!=', '0');
            },
            'target' => function ($query) {
                $query->where('angsuran_ke', '1');
            }
        ])->withCount('pinjaman_anggota')->withCount([
            'rencana' => function ($query) {
                $query->where('angsuran_ke', '!=', '0');
            }
        ])->withCount('real')->first();
        $data['barcode'] = DNS1D::getBarcodePNG($id, 'C128');

        $data['dir'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();


        $data['laporan'] = 'Kartu Angsuran ' . $data['pinkel']->kelompok->nama_kelompok;
        $data['laporan'] .= ' Loan ID. ' . $id;
        $data['angsuran'] = (str_contains(url()->previous(), 'detail') && !($data['pinkel']->status == 'L' || $data['pinkel']->status == 'R')) ? false : true;
        return view('perguliran.dokumen.kartu_angsuran', $data);
    }

    public function kartuAngsuranAnggota($id, $nia = null)
    {
        $data['nia'] = $nia;
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'jpp',
            'sis_pokok',
            'real',
            'real.transaksi',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
        ])->withCount('real')->first();

        $data['generate'] = $this->generate($id, $data['pinkel'])->getData();
        $data['barcode'] = DNS1D::getBarcodePNG($id, 'C128');

        $data['dir'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $data['laporan'] = 'Kartu Angsuran Anggota ' . $data['pinkel']->kelompok->nama_kelompok;
        if ($nia != null) {
            $anggota = PinjamanAnggota::where([
                ['id_pinkel', $id],
                ['nia', $nia]
            ])->with('anggota')->first();

            if (!$anggota) abort(404);

            $data['laporan'] = 'Kartu Angsuran ' . $anggota->anggota->namadepan . ' - ' . $data['pinkel']->kelompok->nama_kelompok;
        }

        $data['laporan'] .= ' Loan ID. ' . $id;
        $data['angsuran'] = (str_contains(url()->previous(), 'detail') && $data['pinkel']->status != 'L') ? false : true;
        return view('perguliran.dokumen.kartu_angsuran_anggota', $data);
    }

    public function cetakKartuAngsuranAnggota($id, $idtp, $nia = null)
    {
        $data['idtp'] = $idtp;
        $data['nia'] = $nia;
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'jpp',
            'sis_pokok',
            'real',
            'real.transaksi',
            'real.transaksi',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
        ])->withCount('real')->first();

        $data['generate'] = $this->generate($id, $data['pinkel'])->getData();
        $data['barcode'] = DNS1D::getBarcodePNG($id, 'C128');

        $data['dir'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $data['laporan'] = 'Kartu Angsuran Anggota ' . $data['pinkel']->kelompok->nama_kelompok;
        if ($nia != null) {
            $anggota = PinjamanAnggota::where([
                ['id_pinkel', $id],
                ['nia', $nia]
            ])->with('anggota')->first();

            if (!$anggota) abort(404);

            $data['laporan'] = 'Kartu Angsuran ' . $anggota->anggota->namadepan . ' - ' . $data['pinkel']->kelompok->nama_kelompok;
        }

        $data['laporan'] .= ' Loan ID. ' . $id;
        return view('perguliran.dokumen.cetak_kartu_angsuran_anggota', $data);
    }

    public function pemberitahuanDesa($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['judul'] = 'Pemberitahuan Ke Desa (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pemberitahuan_desa', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function tanggungRentengKematian($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Tanggung Renteng Kematian (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanggung_renteng_kematian', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function pernyataanTanggungRenteng($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota' => function ($query) {
                $query->where('alokasi', '>=', '0');
            },
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.d',
            'pinjaman_anggota.anggota.d.sebutan_desa',
        ])->first();

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Pernyataan Tanggung Renteng (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pernyataan_tanggung_renteng', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function kuitansi($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['bend'] = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['bp'] = User::where([
            ['level', '3'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['keuangan'] = $keuangan;

        $jenis_dokumen = request()->get('jenis') ?: 'dokumen_proposal';
        $dokumenPinjaman = DokumenPinjaman::where([
            ['file', $data['report']],
            ['jenis_dokumen', $jenis_dokumen]
        ])->with('tanda_tangan')->first();
        $data['tanda_tangan'] = '';
        if ($dokumenPinjaman->tanda_tangan) {
            $data['tanda_tangan'] = Pinjaman::keyword($dokumenPinjaman->tanda_tangan->tanda_tangan, $data);
        }

        $data['judul'] = 'Kuitansi Pencairan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.kuitansi', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function kuitansiAnggota($id, $data)
    {
        $keuangan = new Keuangan;

        $tb_pinjaman = 'pinjaman_anggota_' . Session::get('lokasi');
        $tb_anggota = 'anggota_' . Session::get('lokasi');

        $data['pinjaman'] = PinjamanAnggota::leftJoin($tb_anggota, $tb_anggota . '.id', '=', $tb_pinjaman . '.nia')->with([
            'kelompok',
            'pinkel',
        ])->where($tb_pinjaman . '.id_pinkel', $id)->orderBy($tb_pinjaman . '.id', 'ASC')->get();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['bend'] = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['bp'] = User::where([
            ['level', '3'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Kuitansi Pencairan Anggota Loan ID. ' . $id;
        $view = view('perguliran.dokumen.kuitansi_anggota', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view)->setPaper('A4', 'potrait');
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratTagihan($id, $data)
    {
        $keuangan = new Keuangan;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'sis_pokok'
        ])->first();

        $data['real'] = RealAngsuran::where('loan_id', $id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $data['ra'] = RencanaAngsuran::where([
            ['loan_id', $id],
            ['jatuh_tempo', '<=', date('Y-m-d')]
        ])->orderBy('jatuh_tempo', 'DESC')->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Tagihan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tagihan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratAhliWaris($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
            'pinjaman_anggota.anggota.keluarga',
            'pinjaman_anggota.anggota.d',
            'pinjaman_anggota.anggota.d.sebutan_desa',
        ])->withCount('pinjaman_anggota')->first();

        $data['judul'] = 'Surat Ahli Waris (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.surat_ahli_waris', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function cetakPadaKartu($id, $idtp)
    {
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'jpp',
            'sis_pokok',
            'real',
            'rencana' => function ($query) {
                $query->where('angsuran_ke', '!=', '0');
            },
            'target' => function ($query) {
                $query->where('angsuran_ke', '1');
            }
        ])->withCount('pinjaman_anggota')->withCount('rencana')->first();
        $data['barcode'] = DNS1D::getBarcodePNG($data['pinkel']->kelompok->kd_kelompok, 'C128');

        $data['idtp'] = $idtp;
        $data['dir'] = User::where([
            ['lokasi', Session::get('lokasi')],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $data['laporan'] = 'Kartu Angsuran ' . $data['pinkel']->kelompok->nama_kelompok;
        return view('perguliran.dokumen.cetak_kartu_angsuran', $data);
    }

    public function CetakCatatanBimbingan($id, $data)
    {
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok'
        ])->withCount([
            'pinjaman_anggota' => function ($query) {
                $query->where('status', 'A');
            }
        ])->first();

        $catatan = collect(json_decode($data['pinkel']->catatan_bimbingan, true));
        $data['catatan'] = $catatan->sortByDesc('tanggal')->values();

        $data_user = [];
        $user = $catatan->pluck('user')->unique();
        $users = User::whereIn('id', $user)->get();
        foreach ($users as $user) {
            $data_user[$user->id] = $user->namadepan . ' ' . $user->namabelakang;
        }
        $data['users'] = $data_user;

        $data['judul'] = 'Catatan Bimbingan ' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id;
        $view = view('perguliran.dokumen.catatan_bimbingan', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function suratVerifikasi($id, $data)
    {
        $keuangan = new Keuangan;

        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'jasa',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->first();

        $data['keuangan'] = $keuangan;
        $data['statusDokumen'] = request()->get('status');

        $data['judul'] = 'Surat Verifikasi (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.surat_verifikasi', $data)->render();

        if ($data['type'] == 'pdf') {
            $pdf = PDF::loadHTML($view);
            return $pdf->stream();
        } else {
            return $view;
        }
    }

    public function generate($id_pinj, $pinkel = null)
    {
        $rencana = [];
        $data_rencana = [];
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $is_generate_kelompok = false;
        if ($pinkel == null) {
            $pinkel = PinjamanKelompok::where('id', $id_pinj)->with([
                'kelompok',
                'kelompok.d',
                'saldo_pinjaman' => function ($query) {
                    $query->where('lokasi', Session::get('lokasi'))->orderBy('tanggal', 'DESC');
                },
                'pinjaman_anggota',
                'sis_pokok',
                'sis_jasa',
            ])->firstOrFail();

            $is_generate_kelompok = true;
        }

        $data_pinjaman[] = $pinkel->id;
        $desa = $pinkel->kelompok->d;

        $jangka = $pinkel->jangka;
        $sistem_angsuran_pokok = $pinkel->sistem_angsuran;
        $sistem_angsuran_jasa = $pinkel->sa_jasa;
        $sistem_pokok = ($pinkel->sis_pokok) ? $pinkel->sis_pokok->sistem : '1';
        $sistem_jasa = ($pinkel->sis_jasa) ? $pinkel->sis_jasa->sistem : '1';

        $angsuran_pokok = $this->sistem($sistem_angsuran_pokok, $jangka, $sistem_pokok);
        $angsuran_jasa = $this->sistem($sistem_angsuran_jasa, $jangka, $sistem_jasa);

        $rencana_angsuran_anggota = [];
        if (count($pinkel->pinjaman_anggota) > 0 && $is_generate_kelompok == false) {
            $alokasi = 0;
            $rencana_pokok = [];
            $rencana_jasa = [];
            $alokasi_jasa_anggota = [];
            foreach ($pinkel->pinjaman_anggota as $pinjaman_anggota) {
                $pros_jasa_anggota = $pinjaman_anggota->pros_jasa;
                if (Session::get('lokasi') == '522') {
                    $pros_jasa_kelompok = ($pinkel->pros_jasa / $pinkel->jangka) + 0.2;
                    if ($pinkel->pinjaman_anggota >= '3') {
                        $pros_jasa_anggota = $pros_jasa_kelompok * $pinkel->jangka;
                    }
                }

                $detail_pinjaman = $this->detail_pinjaman($pinjaman_anggota, $desa, $kec->batas_angsuran);
                $alokasi_anggota = $detail_pinjaman['alokasi'];
                $alokasi_jasa_anggota[$pinjaman_anggota->id] = $alokasi_anggota * ($pros_jasa_anggota / 100);
                $tgl_cair = $detail_pinjaman['tgl_cair'];

                $rencana_angsuran = $this->rencana_angsuran($pinjaman_anggota, $angsuran_pokok, $angsuran_jasa, $alokasi_anggota, $kec->pembulatan, $pros_jasa_anggota);
                $pokok = $rencana_angsuran['pokok'];
                $jasa = $rencana_angsuran['jasa'];

                if (count($rencana_pokok) <= 0) {
                    $rencana_pokok = $pokok;
                } else {
                    foreach ($pokok as $key => $value) {
                        $rencana_pokok[$key] = ($rencana_pokok[$key] ?? 0) + $value;
                    }
                }

                if (count($rencana_jasa) <= 0) {
                    $rencana_jasa = $jasa;
                } else {
                    foreach ($jasa as $key => $value) {
                        $rencana_jasa[$key] = ($rencana_jasa[$key] ?? 0) + $value;
                    }
                }

                $rencana_angsuran_anggota['id' . $pinjaman_anggota->id] = [
                    'pokok' => $pokok,
                    'jasa' => $jasa,
                    'alokasi' => $alokasi_anggota,
                    'alokasi_jasa' => $alokasi_jasa_anggota[$pinjaman_anggota->id],
                    'jumlah_angsuran' => $rencana_angsuran['jumlah_angsuran']
                ];

                $alokasi += $alokasi_anggota;
            }
        } else {
            $detail_pinjaman = $this->detail_pinjaman($pinkel, $desa, $kec->batas_angsuran);
            $alokasi = $detail_pinjaman['alokasi'];
            $tgl_cair = $detail_pinjaman['tgl_cair'];

            $rencana_angsuran = $this->rencana_angsuran($pinkel, $angsuran_pokok, $angsuran_jasa, $alokasi, $kec->pembulatan);
            $rencana_pokok = $rencana_angsuran['pokok'];
            $rencana_jasa = $rencana_angsuran['jasa'];
        }

        $target_pokok = 0;
        $target_jasa = 0;

        $data_rencana[strtotime($tgl_cair)] = [
            'loan_id' => $pinkel->id,
            'angsuran_ke' => 0,
            'jatuh_tempo' => $tgl_cair,
            'wajib_pokok' => 0,
            'wajib_jasa' => 0,
            'target_pokok' => $target_pokok,
            'target_jasa' => $target_jasa,
            'lu' => date('Y-m-d H:i:s'),
            'id_user' => auth()->user()->id
        ];

        $alokasi_jasa = ($alokasi * ($pinkel->pros_jasa / 100));
        for ($i = 1; $i <= $jangka; $i++) {
            $angsuran_ke = $i;
            $jatuh_tempo = $this->jatuh_tempo($i, $sistem_angsuran_pokok, $tgl_cair);

            $sisa_pokok = $i % $angsuran_pokok['sistem'];
            $sisa_jasa = $i % $angsuran_jasa['sistem'];
            $ke_pokok = $i / $angsuran_pokok['sistem'];
            $ke_jasa = $i / $angsuran_jasa['sistem'];

            $pokok = $rencana_pokok[$i] ?: 0;
            $jasa = $rencana_jasa[$i] ?: 0;

            $target_jasa += $jasa;
            $target_pokok += $pokok;
            if ($i == 1) {
                $target_pokok = $pokok;
                $target_jasa = $jasa;
            }

            $data_rencana[strtotime($jatuh_tempo)] = [
                'loan_id' => $pinkel->id,
                'angsuran_ke' => $angsuran_ke,
                'jatuh_tempo' => $jatuh_tempo,
                'wajib_pokok' => $pokok,
                'wajib_jasa' => $jasa,
                'target_pokok' => $target_pokok,
                'target_jasa' => $target_jasa,
                'lu' => date('Y-m-d H:i:s'),
                'id_user' => auth()->user()->id
            ];
            $rencana[] = $data_rencana[strtotime($jatuh_tempo)];
        }

        if (request()->get('save')) {
            RencanaAngsuran::where('loan_id', $id_pinj)->delete();
            RencanaAngsuran::insert($data_rencana);
        }

        return response()->json([
            'success' => true,
            'rencana_angsuran' => $rencana,
            'rencana_angsuran_anggota' => $rencana_angsuran_anggota
        ], Response::HTTP_OK);
    }

    public function _generate($id_pinj, $pinkel = null, $alokasi = null, $tgl = null, $pros_jasa = null)
    {
        $rencana = [];
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        if ($alokasi == null && $tgl == null) {
            $pinkel = PinjamanKelompok::where('id', $id_pinj)->with([
                'kelompok',
                'kelompok.d',
                'saldo_pinjaman' => function ($query) {
                    $query->where('lokasi', Session::get('lokasi'))->orderBy('tanggal', 'DESC');
                }
            ])->firstOrFail();

            if ($pinkel->status == 'P') {
                $alokasi = $pinkel->proposal;
                $tgl = $pinkel->tgl_proposal;
            } elseif ($pinkel->status == 'V') {
                $alokasi = $pinkel->verifikasi;
                $tgl = $pinkel->tgl_verifikasi;
            } elseif ($pinkel->status == 'W') {
                $alokasi = $pinkel->alokasi;
                $tgl = $pinkel->tgl_cair;
            } else {
                $alokasi = $pinkel->alokasi;
                $tgl = $pinkel->tgl_cair;
            }

            if (request()->get('status')) {
                if (request()->get('status') == 'P') {
                    $alokasi = $pinkel->proposal;
                    $tgl = $pinkel->tgl_proposal;
                } elseif (request()->get('status') == 'V') {
                    $alokasi = $pinkel->verifikasi;
                    $tgl = $pinkel->tgl_verifikasi;
                } elseif (request()->get('status') == 'W') {
                    $alokasi = $pinkel->alokasi;
                    $tgl = $pinkel->tgl_cair;
                } else {
                    $alokasi = $pinkel->alokasi;
                    $tgl = $pinkel->tgl_cair;
                }
            }
        }

        $jenis_jasa = $pinkel->jenis_jasa;
        $jangka = $pinkel->jangka;
        $sa_pokok = $pinkel->sistem_angsuran;
        $sa_jasa = $pinkel->sa_jasa;
        if ($pros_jasa == null) {
            $pros_jasa = $pinkel->pros_jasa;
        }

        $tgl_angsur = $tgl;
        $tanggal_cair = date('d', strtotime($tgl));

        if ($pinkel->kelompok->d) {
            $angsuran_desa = $pinkel->kelompok->d->jadwal_angsuran_desa;
            if ($angsuran_desa > 0) {
                $tgl_pinjaman = date('Y-m', strtotime($tgl));
                $tgl = $tgl_pinjaman . '-' . $angsuran_desa;
            }
        }

        if ($kec->batas_angsuran > 0) {
            $batas_tgl_angsuran = $kec->batas_angsuran;
            if ($tanggal_cair >= $batas_tgl_angsuran) {
                $tgl = date('Y-m-d', strtotime('+1 month', strtotime($tgl)));
            }
        }

        $sistem_pokok = $pinkel->sis_pokok->sistem;
        $sistem_jasa = $pinkel->sis_jasa->sistem;

        if ($sa_pokok == 11) {
            $tempo_pokok = ($jangka) - 24 / $sistem_pokok;
            $mulai_angsuran_pokok = $jangka - $tempo_pokok;
        } else if ($sa_pokok == 14) {
            $tempo_pokok = ($jangka) - 3 / $sistem_pokok;
            $mulai_angsuran_pokok = $jangka - $tempo_pokok;
        } else if ($sa_pokok == 15) {
            $tempo_pokok = ($jangka) - 2 / $sistem_pokok;
            $mulai_angsuran_pokok = $jangka - $tempo_pokok;
        } else if ($sa_pokok == 25) {
            $tempo_pokok = ($jangka) - 1 / $sistem_pokok;
            $mulai_angsuran_pokok = $jangka - $tempo_pokok;
        } else if ($sa_pokok == 20) {
            $tempo_pokok = ($jangka) - 12 / $sistem_pokok;
            $mulai_angsuran_pokok = $jangka - $tempo_pokok;
        } else {
            $tempo_pokok = floor($jangka / $sistem_pokok);
            $mulai_angsuran_pokok = 0;
        }

        if ($sa_jasa == 11) {
            $tempo_jasa = ($jangka) - 24 / $sistem_jasa;
            $mulai_angsuran_jasa = $jangka - $tempo_jasa;
        } else if ($sa_jasa == 14) {
            $tempo_jasa = ($jangka) - 3 / $sistem_jasa;
            $mulai_angsuran_jasa = $jangka - $tempo_jasa;
        } else if ($sa_jasa == 15) {
            $tempo_jasa = ($jangka) - 2 / $sistem_jasa;
            $mulai_angsuran_jasa = $jangka - $tempo_jasa;
        } else if ($sa_pokok == 25) {
            $tempo_jasa = ($jangka) - 1 / $sistem_jasa;
            $mulai_angsuran_jasa = $jangka - $tempo_jasa;
        } else if ($sa_jasa == 20) {
            $tempo_jasa = ($jangka) - 12 / $sistem_jasa;
            $mulai_angsuran_jasa = $jangka - $tempo_jasa;
        } else {
            $tempo_jasa = floor($jangka / $sistem_jasa);
            $mulai_angsuran_jasa = 0;
        }

        $ra = [];
        $alokasi_pokok = $alokasi;
        if ($jenis_jasa == '1') {
            for ($j = 1; $j <= $jangka; $j++) {
                $sisa = $j % $sistem_jasa;
                $ke = $j / $sistem_jasa;

                $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);
                $wajib_jasa = $alokasi_jasa / $tempo_jasa;
                $wajib_jasa = Keuangan::pembulatan($wajib_jasa, (string) $kec->pembulatan);
                $sum_jasa = $wajib_jasa * ($tempo_jasa - 1);

                if ($sisa == 0 and $ke != $tempo_jasa && $ke > $mulai_angsuran_jasa) {
                    $angsuran_jasa = $wajib_jasa;
                } elseif ($sisa == 0 and $ke == $tempo_jasa) {
                    $angsuran_jasa = $alokasi_jasa - $sum_jasa;
                } else {
                    $angsuran_jasa = 0;
                }

                if ($jenis_jasa == '2') {
                    $angsuran_jasa = $wajib_jasa;
                    $alokasi_pokok -= $ra[$j]['pokok'];
                }

                $ra[$j]['jasa'] = $angsuran_jasa;
            }
        }

        for ($i = 1; $i <= $jangka; $i++) {
            $sisa = $i % $sistem_pokok;
            $ke = $i / $sistem_pokok;

            $wajib_pokok = Keuangan::pembulatan($alokasi / $tempo_pokok, (string) $kec->pembulatan);
            if ($jenis_jasa == '1') {
                $wajib_pokok = ($alokasi / 10) - $ra[$i]['jasa'];
                if ($jangka == 24) {
                    $wajib_pokok = Keuangan::pembulatan((($alokasi / 10) - $ra[$i]['jasa']) / 2, -500);

                    if ($alokasi > 1000000) {
                        $wajib_pokok = Keuangan::pembulatan((($alokasi / 10) - $ra[$i]['jasa']) / 2, 5000);
                    }

                    if ($alokasi != 20000000) {
                        if ($alokasi >= 8000000) {
                            $wajib_pokok -= 5000;
                        }

                        if ($alokasi == 12000000 || $alokasi >= 14000000) {
                            $wajib_pokok -= 5000;
                        }

                        if ($alokasi == 18000000 || $alokasi == 6000000) {
                            $wajib_pokok -= 5000;
                        }
                    }
                }

                if ($kec->pembulatan != '5000') {
                    $wajib_pokok = Keuangan::pembulatan($alokasi / $tempo_pokok, (string) $kec->pembulatan);
                }
            }

            $sum_pokok = $wajib_pokok * ($tempo_pokok - 1);

            if ($sisa == 0 and $ke != $jangka && $ke > $mulai_angsuran_pokok) {
                $angsuran_pokok = $wajib_pokok;
            } elseif ($sisa == 0 and $ke == $jangka) {
                $angsuran_pokok = $alokasi - $sum_pokok;
            } else {
                $angsuran_pokok = 0;
            }

            $ra[$i]['pokok'] = $angsuran_pokok;
        }

        if ($jenis_jasa != '1') {
            for ($j = 1; $j <= $jangka; $j++) {
                $sisa = $j % $sistem_jasa;
                $ke = $j / $sistem_jasa;

                $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);
                $wajib_jasa = $alokasi_jasa / $tempo_jasa;
                $wajib_jasa = Keuangan::pembulatan($wajib_jasa, (string) $kec->pembulatan);
                $sum_jasa = $wajib_jasa * ($tempo_jasa - 1);

                if ($sisa == 0 and $ke != $tempo_jasa && $ke > $mulai_angsuran_jasa) {
                    $angsuran_jasa = $wajib_jasa;
                } elseif ($sisa == 0 and $ke == $tempo_jasa) {
                    $angsuran_jasa = $alokasi_jasa - $sum_jasa;
                } else {
                    $angsuran_jasa = 0;
                }

                if ($jenis_jasa == '2') {
                    $angsuran_jasa = $wajib_jasa;
                    $alokasi_pokok -= $ra[$j]['pokok'];
                }

                $ra[$j]['jasa'] = $angsuran_jasa;
            }
        }

        $ra['alokasi'] = $alokasi;

        if (request()->get('save')) {
            $insert_ra = [];

            RencanaAngsuran::where('loan_id', $id_pinj)->delete();
            RencanaAngsuran::create([
                'loan_id' => $id_pinj,
                'angsuran_ke' => '0',
                'jatuh_tempo' => $tgl,
                'wajib_pokok' => '0',
                'wajib_jasa' => '0',
                'target_pokok' => '0',
                'target_jasa' => '0',
                'lu' => date('Y-m-d H:i:s'),
                'id_user' => auth()->user()->id
            ]);

            $target_pokok = 0;
            $target_jasa = 0;
            for ($x = 1; $x <= $jangka; $x++) {
                $bulan  = substr($tgl, 5, 2);
                $tahun  = substr($tgl, 0, 4);

                if ($sa_pokok == 12) {
                    $tambah = $x * 7;
                    $penambahan = "+$tambah days";
                } else {
                    $penambahan = "+$x month";
                }
                $jatuh_tempo = date('Y-m-d', strtotime($penambahan, strtotime($tgl)));

                $pokok = $ra[$x]['pokok'];
                $jasa = $ra[$x]['jasa'];

                if ($x == 1) {
                    $target_pokok = $pokok;
                } elseif ($x >= 2) {
                    $target_pokok += $pokok;
                }
                if ($x == 1) {
                    $target_jasa = $jasa;
                } elseif ($x >= 2) {
                    $target_jasa += $jasa;
                }

                $insert_ra[] = [
                    'loan_id' => $id_pinj,
                    'angsuran_ke' => $x,
                    'jatuh_tempo' => $jatuh_tempo,
                    'wajib_pokok' => $pokok,
                    'wajib_jasa' => $jasa,
                    'target_pokok' => $target_pokok,
                    'target_jasa' => $target_jasa,
                    'lu' => date('Y-m-d H:i:s'),
                    'id_user' => auth()->user()->id
                ];
            }

            RencanaAngsuran::insert($insert_ra);
        } else {
            $target_pokok = 0;
            $target_jasa = 0;
            for ($x = 1; $x <= $jangka; $x++) {
                $bulan  = substr($tgl, 5, 2);
                $tahun  = substr($tgl, 0, 4);

                if ($sa_pokok == 12) {
                    $tambah = $x * 7;
                    $penambahan = "+$tambah days";
                } else {
                    $penambahan = "+$x month";
                }

                $tanggal_cair = date('Y-m', strtotime($tgl));
                $jatuh_tempo = date('Y-m', strtotime($penambahan, strtotime($tanggal_cair)));

                if (date('d', strtotime($tgl)) > date('t', strtotime($jatuh_tempo))) {
                    $jatuh_tempo = date('Y-m-t', strtotime($jatuh_tempo));
                } else {
                    $jatuh_tempo = date('Y-m', strtotime($jatuh_tempo)) . '-' . date('d', strtotime($tgl));
                }

                $pokok = $ra[$x]['pokok'];
                $jasa = $ra[$x]['jasa'];

                if ($x == 1) {
                    $target_pokok = $pokok;
                } elseif ($x >= 2) {
                    $target_pokok += $pokok;
                }
                if ($x == 1) {
                    $target_jasa = $jasa;
                } elseif ($x >= 2) {
                    $target_jasa += $jasa;
                }

                $rencana[] = [
                    'loan_id' => $id_pinj,
                    'angsuran_ke' => $x,
                    'jatuh_tempo' => $jatuh_tempo,
                    'wajib_pokok' => $pokok,
                    'wajib_jasa' => $jasa,
                    'target_pokok' => $target_pokok,
                    'target_jasa' => $target_jasa,
                    'lu' => date('Y-m-d H:i:s'),
                    'id_user' => auth()->user()->id
                ];
            }
        }

        return response()->json([
            'success' => true,
            'ra' => $ra,
            'rencana' => $rencana
        ], Response::HTTP_OK);
    }

    private function sistem($sistem_angsuran, $jangka_pinjaman, $sistem)
    {
        if ($sistem_angsuran == 11) {
            $tempo = ($jangka_pinjaman) - 24 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 14) {
            $tempo = ($jangka_pinjaman) - 3 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 15) {
            $tempo = ($jangka_pinjaman) - 2 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 25) {
            $tempo = ($jangka_pinjaman) - 1 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else if ($sistem_angsuran == 20) {
            $tempo = ($jangka_pinjaman) - 12 / $sistem;
            $mulai_angsuran = $jangka_pinjaman - $tempo;
        } else {
            $tempo = floor($jangka_pinjaman / $sistem);
            $mulai_angsuran = 0;
        }

        return [
            'tempo' => $tempo,
            'sistem' => $sistem,
            'mulai_angsuran' => $mulai_angsuran,
        ];
    }

    private function detail_pinjaman($pinjaman, $desa, $batas_angsuran)
    {
        if ($pinjaman->status == 'P') {
            $alokasi = $pinjaman->proposal;
            $tgl_cair = $pinjaman->tgl_proposal;
        } elseif ($pinjaman->status == 'V') {
            $alokasi = $pinjaman->verifikasi;
            $tgl_cair = $pinjaman->tgl_verifikasi;
        } elseif ($pinjaman->status == 'W') {
            $alokasi = $pinjaman->alokasi;
            $tgl_cair = $pinjaman->tgl_cair;

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinjaman->tgl_tunggu;
            }
        } else {
            $alokasi = $pinjaman->alokasi;
            $tgl_cair = $pinjaman->tgl_cair;

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinjaman->tgl_tunggu;
            }
        }

        $tanggal_cair = date('d', strtotime($tgl_cair));
        if ($desa->jadwal_angsuran_desa > 0) {
            $angsuran_desa = $desa->jadwal_angsuran_desa;
            if ($angsuran_desa > 0) {
                $tgl_pinjaman = date('Y-m', strtotime($tgl_cair));
                $tgl_cair = $tgl_pinjaman . '-' . $angsuran_desa;
            }
        }

        if ($batas_angsuran > 0) {
            $batas_tgl_angsuran = $batas_angsuran;
            if ($tanggal_cair >= $batas_tgl_angsuran) {
                $tgl_cair = date('Y-m-d', strtotime('+1 month', strtotime($tgl_cair)));
            }
        }

        return [
            'alokasi' => $alokasi,
            'tgl_cair' => $tgl_cair
        ];
    }

    private function rencana_angsuran($pinkel, $angsuran_pokok, $angsuran_jasa, $alokasi, $pembulatan = '500', $pros_jasa = null)
    {
        $pros_jasa = $pros_jasa ?: $pinkel->pros_jasa;

        $rencana_angsuran['pokok'] = [];
        $rencana_angsuran['jasa'] = [];
        $rencana_angsuran['jumlah_angsuran'] = 0;
        $alokasi_pokok = $alokasi;
        for ($j = 1; $j <= $pinkel->jangka; $j++) {
            $sisa_pokok = $j % $angsuran_pokok['sistem'];
            $sisa_jasa = $j % $angsuran_jasa['sistem'];
            $ke_pokok = $j / $angsuran_pokok['sistem'];
            $ke_jasa = $j / $angsuran_jasa['sistem'];

            $alokasi_jasa = $alokasi * ($pros_jasa / 100);
            $wajib_angsuran_jasa = $alokasi_jasa / $angsuran_jasa['tempo'];
            $wajib_angsuran_jasa = Keuangan::pembulatan(intval($wajib_angsuran_jasa), (string) $pembulatan);
            $sum_angsuran_jasa = $wajib_angsuran_jasa * ($angsuran_jasa['tempo'] - 1);

            if ($sisa_jasa == 0 and $ke_jasa != $pinkel->jangka && $ke_jasa > $angsuran_jasa['mulai_angsuran']) {
                $jasa = $wajib_angsuran_jasa;
            } elseif ($sisa_jasa == 0 and $ke_jasa == $pinkel->jangka) {
                $jasa = $alokasi_jasa - $sum_angsuran_jasa;
            } else {
                $jasa = 0;
            }

            $wajib_angsuran_pokok = $alokasi_pokok / $angsuran_pokok['tempo'];
            $wajib_angsuran_pokok = Keuangan::pembulatan(intval($wajib_angsuran_pokok), (string) $pembulatan);

            if ($pinkel->jenis_jasa == '1' && $pembulatan == '5000') {
                $wajib_angsuran_pokok = ($alokasi_pokok / 10) - $jasa;
                if ($pinkel->jangka == 24) {
                    $wajib_angsuran_pokok = Keuangan::pembulatan((($alokasi_pokok / 10) - $jasa / 2), -500);

                    if ($alokasi_pokok > 1000000) {
                        $wajib_angsuran_pokok = Keuangan::pembulatan((($alokasi_pokok / 10) - $jasa / 2), 5000);
                    }

                    if ($alokasi_pokok != 20000000) {
                        if ($alokasi_pokok >= 8000000) {
                            $wajib_angsuran_pokok -= 5000;
                        }

                        if ($alokasi_pokok == 12000000 || $alokasi_pokok >= 14000000) {
                            $wajib_angsuran_pokok -= 5000;
                        }

                        if ($alokasi_pokok == 18000000 || $alokasi_pokok == 6000000) {
                            $wajib_angsuran_pokok -= 5000;
                        }
                    }
                }
            }

            $sum_angsuran_pokok = $wajib_angsuran_pokok * ($angsuran_pokok['tempo'] - 1);

            if ($sisa_pokok == 0 && $ke_pokok != $pinkel->jangka && $ke_pokok > $angsuran_pokok['mulai_angsuran']) {
                $pokok = $wajib_angsuran_pokok;
            } elseif ($sisa_pokok == 0 && $ke_pokok == $pinkel->jangka) {
                $pokok = $alokasi_pokok - $sum_angsuran_pokok;
            } else {
                $pokok = 0;
            }
            $rencana_angsuran['pokok'][$j] = $pokok;

            if ($pinkel->jenis_jasa == '2') {
                $alokasi -= $rencana_angsuran['pokok'][$j];
            }

            $rencana_angsuran['jasa'][$j] = $jasa;
            if ($rencana_angsuran['jumlah_angsuran'] == 0) {
                $rencana_angsuran['jumlah_angsuran'] = $pokok + $jasa;
            }
        }

        return $rencana_angsuran;
    }

    private function jatuh_tempo($index, $sistem_angsuran_pokok, $tanggal)
    {
        if ($sistem_angsuran_pokok == 12) {
            $tambah = $index * 7;
            $penambahan = "+$tambah days";
        } else {
            $penambahan = "+$index month";
        }

        $tanggal_cair = date('Y-m', strtotime($tanggal));
        $jatuh_tempo = date('Y-m', strtotime($penambahan, strtotime($tanggal_cair)));

        if (date('d', strtotime($tanggal)) > date('t', strtotime($jatuh_tempo))) {
            $jatuh_tempo = date('Y-m-t', strtotime($jatuh_tempo));
        } else {
            $jatuh_tempo = date('Y-m', strtotime($jatuh_tempo)) . '-' . date('d', strtotime($tanggal));
        }

        return $jatuh_tempo;
    }

    public function generateRA($id_pinj)
    {
        $rencana = [];
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        $pinkel = PinjamanKelompok::where('id', $id_pinj)->firstOrFail();

        $jangka = $pinkel->jangka;
        $sa_pokok = $pinkel->sistem_angsuran;
        $sa_jasa = $pinkel->sa_jasa;
        $pros_jasa = $pinkel->pros_jasa;

        if ($pinkel->status == 'P') {
            $alokasi = $pinkel->proposal;
            $tgl = $pinkel->tgl_proposal;
        } elseif ($pinkel->status == 'V') {
            $alokasi = $pinkel->verifikasi;
            $tgl = $pinkel->tgl_verifikasi;
        } elseif ($pinkel->status == 'W') {
            $alokasi = $pinkel->alokasi;
            $tgl = $pinkel->tgl_tunggu;
        } else {
            $alokasi = $pinkel->alokasi;
            $tgl = $pinkel->tgl_cair;
        }

        if (request()->get('status')) {
            $status = request()->get('status');
            if ($status == 'P') {
                $alokasi = $pinkel->proposal;
                $tgl = $pinkel->tgl_proposal;
            } elseif ($status == 'V') {
                $alokasi = $pinkel->verifikasi;
                $tgl = $pinkel->tgl_verifikasi;
            } elseif ($status == 'W') {
                $alokasi = $pinkel->alokasi;
                $tgl = $pinkel->tgl_tunggu;
            } else {
                $alokasi = $pinkel->alokasi;
                $tgl = $pinkel->tgl_cair;
            }
        }

        $sistem_pokok = $pinkel->sis_pokok->sistem;
        $sistem_jasa = $pinkel->sis_jasa->sistem;

        if ($sa_pokok == 11) {
            $tempo_pokok        = ($jangka) - 24 / $sistem_pokok;
        } else if ($sa_pokok == 14) {
            $tempo_pokok        = ($jangka) - 3 / $sistem_pokok;
        } else if ($sa_pokok == 15) {
            $tempo_pokok        = ($jangka) - 2 / $sistem_pokok;
        } else if ($sa_pokok == 20) {
            $tempo_pokok        = ($jangka) - 12 / $sistem_pokok;
        } else {
            $tempo_pokok        = floor($jangka / $sistem_pokok);
        }

        if ($sa_jasa == 11) {
            $tempo_jasa        = ($jangka) - 24 / $sistem_jasa;
        } else if ($sa_jasa == 14) {
            $tempo_jasa        = ($jangka) - 3 / $sistem_jasa;
        } else if ($sa_jasa == 15) {
            $tempo_jasa        = ($jangka) - 2 / $sistem_jasa;
        } else if ($sa_jasa == 20) {
            $tempo_jasa        = ($jangka) - 12 / $sistem_jasa;
        } else {
            $tempo_jasa        = floor($jangka / $sistem_jasa);
        }

        $ra = [];

        // Rencana Angsuran Pokok
        for ($i = 1; $i <= $jangka; $i++) {
            $sisa = $i % $sistem_pokok;
            $ke = $i / $sistem_pokok;
            $wajib_pokok = Keuangan::pembulatan($alokasi / $tempo_pokok, (string) $kec->pembulatan);
            $sum_pokok = $wajib_pokok * ($tempo_pokok - 1);

            if ($sisa == 0 and $ke != $tempo_pokok) {
                $angsuran_pokok = $wajib_pokok;
            } elseif ($sisa == 0 and $ke == $tempo_pokok) {
                $angsuran_pokok = $alokasi - $sum_pokok;
            } else {
                $angsuran_pokok = 0;
            }

            $ra[$i]['pokok'] = $angsuran_pokok;
        }

        // Rencana Angsuran Jasa
        for ($j = 1; $j <= $jangka; $j++) {
            $sisa = $j % $sistem_jasa;
            $ke = $j / $sistem_jasa;
            $sum_jasa = $alokasi * ($pros_jasa / 100);
            $wajib_jasa = Keuangan::pembulatan($sum_jasa / $tempo_jasa, (string) $kec->pembulatan);

            if ($sisa == 0) {
                $angsuran_jasa = $wajib_jasa;
            } else {
                $angsuran_jasa = 0;
            }

            $ra[$j]['jasa'] = $angsuran_jasa;
        }
        $ra['alokasi'] = $alokasi;

        RencanaAngsuran::where('loan_id', $id_pinj)->delete();

        RencanaAngsuran::create([
            'loan_id' => $id_pinj,
            'angsuran_ke' => '0',
            'jatuh_tempo' => $tgl,
            'wajib_pokok' => '0',
            'wajib_jasa' => '0',
            'target_pokok' => '0',
            'target_jasa' => '0',
            'lu' => date('Y-m-d H:i:s'),
            'id_user' => auth()->user()->id
        ]);

        $target_pokok = 0;
        $target_jasa = 0;
        for ($x = 1; $x <= $jangka; $x++) {
            $bulan  = substr($tgl, 5, 2);
            $tahun  = substr($tgl, 0, 4);

            if ($sa_pokok == 12) {
                $tambah = $x * 7;
                $penambahan = "+$tambah days";
            } else {
                $penambahan = "+$x month";
            }
            $jatuh_tempo = date('Y-m-d', strtotime($penambahan, strtotime($tgl)));

            $pokok = $ra[$x]['pokok'];
            $jasa = $ra[$x]['jasa'];

            if ($x == 1) {
                $target_pokok = $pokok;
            } elseif ($x >= 2) {
                $target_pokok += $pokok;
            }
            if ($x == 1) {
                $target_jasa = $jasa;
            } elseif ($x >= 2) {
                $target_jasa += $jasa;
            }

            RencanaAngsuran::create([
                'loan_id' => $id_pinj,
                'angsuran_ke' => $x,
                'jatuh_tempo' => $jatuh_tempo,
                'wajib_pokok' => $pokok,
                'wajib_jasa' => $jasa,
                'target_pokok' => $target_pokok,
                'target_jasa' => $target_jasa,
                'lu' => date('Y-m-d H:i:s'),
                'id_user' => auth()->user()->id
            ]);
        }
        if (request()->get('save')) {
        } else {
            $target_pokok = 0;
            $target_jasa = 0;
            for ($x = 1; $x <= $jangka; $x++) {
                $bulan  = substr($tgl, 5, 2);
                $tahun  = substr($tgl, 0, 4);

                if ($sa_pokok == 12) {
                    $tambah = $x * 7;
                    $penambahan = "+$tambah days";
                } else {
                    $penambahan = "+$x month";
                }
                $jatuh_tempo = date('Y-m-d', strtotime($penambahan, strtotime($tgl)));

                $pokok = $ra[$x]['pokok'];
                $jasa = $ra[$x]['jasa'];

                if ($x == 1) {
                    $target_pokok = $pokok;
                } elseif ($x >= 2) {
                    $target_pokok += $pokok;
                }
                if ($x == 1) {
                    $target_jasa = $jasa;
                } elseif ($x >= 2) {
                    $target_jasa += $jasa;
                }

                $rencana[] = [
                    'loan_id' => $id_pinj,
                    'angsuran_ke' => $x,
                    'jatuh_tempo' => $jatuh_tempo,
                    'wajib_pokok' => $pokok,
                    'wajib_jasa' => $jasa,
                    'target_pokok' => $target_pokok,
                    'target_jasa' => $target_jasa,
                    'lu' => date('Y-m-d H:i:s'),
                    'id_user' => auth()->user()->id
                ];
            }
        }

        return response()->json([
            'success' => true,
            'ra' => $ra,
            'rencana' => $rencana
        ], Response::HTTP_OK);
    }

    public function excel($filename, $lokasi)
    {
        abort(404);
        $excel = Excel::toArray([], storage_path('app/public/excel/' . $filename . '.xlsx'));

        $id_pinj = [];
        $angsuran = [];
        foreach ($excel[0] as $data) {
            if (is_numeric($data[0])) {
                $id = $data[0];
                $pokok = $data[4];
                $jasa = $data[5];

                $id_pinj[] = $id;
                $angsuran[$id] = [
                    'pokok' => $pokok,
                    'jasa' => $jasa
                ];
            }
        }

        Session::put('lokasi', $lokasi);
        $pinjaman = PinjamanKelompok::whereIn('id', $id_pinj)->with([
            'rencana',
            'real'
        ])->get();

        foreach ($pinjaman as $pinj) {
            $jangka = $pinj->jangka;
            $pros_jasa = $pinj->pros_jasa;
            $alokasi_pokok = $pinj->alokasi;
            $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);

            $pokok = $angsuran[$pinj->id]['pokok'] ?: 0;
            $jasa = $angsuran[$pinj->id]['jasa'] ?: 0;

            $wajib_pokok = 0;
            $wajib_jasa = 0;
            $target_pokok = 0;
            $target_jasa = 0;

            $query_ra = '';
            $query_real = '';

            $rencana = [];
            foreach ($pinj->rencana as $ra) {
                if ($ra->angsuran_ke > 0) {
                    $wajib_pokok = $pokok;
                    $wajib_jasa = $jasa;

                    if ($ra->angsuran_ke == $jangka) {
                        $wajib_pokok = $alokasi_pokok - $target_pokok;
                        $wajib_jasa = $alokasi_jasa - $target_jasa;
                    }

                    $target_pokok += $wajib_pokok;
                    $target_jasa += $wajib_jasa;
                }

                $rencana[strtotime($ra->jatuh_tempo)] = $ra;
                echo "INSERT INTO `rencana_angsuran_$lokasi`(`loan_id`, `angsuran_ke`, `jatuh_tempo`, `wajib_pokok`, `wajib_jasa`, `target_pokok`, `target_jasa`, `lu`, `id_user`) 
                    VALUES ('$pinj->id','$ra->angsuran_ke','$ra->jatuh_tempo','$wajib_pokok','$wajib_jasa','$target_pokok','$target_jasa','$ra->lu','$ra->id_user');<br>";
            }

            echo "<br>";
            foreach ($pinj->real as $real) {
                $data_ra = array_filter(array_keys($rencana), function ($key) use ($real) {
                    return $key <= intval(strtotime($real->tgl_transaksi));
                });

                $max_ra = max($data_ra);
                $ra = $rencana[$max_ra];

                $target_pokok = 0;
                $target_jasa = 0;
                if ($ra) {
                    $target_pokok = $ra->target_pokok;
                    $target_jasa = $ra->target_jasa;
                }

                $tunggakan_pokok = $target_pokok - $real->sum_pokok;
                $tunggakan_jasa = $target_jasa - $real->sum_jasa;

                if ($tunggakan_pokok < 0) {
                    $tunggakan_pokok = 0;
                }

                if ($tunggakan_jasa < 0) {
                    $tunggakan_jasa = 0;
                }

                echo "INSERT INTO `real_angsuran_$lokasi`(`id`, `loan_id`, `tgl_transaksi`, `realisasi_pokok`, `realisasi_jasa`, `sum_pokok`, `sum_jasa`, `saldo_pokok`, `saldo_jasa`, `tunggakan_pokok`, `tunggakan_jasa`, `lu`, `id_user`) 
                        VALUES ('$real->id','$real->loan_id','$real->tgl_transaksi','$real->realisasi_pokok','$real->realisasi_jasa','$real->sum_pokok','$real->sum_jasa','$real->saldo_pokok','$real->saldo_jasa','$tunggakan_pokok','$tunggakan_jasa','$real->lu','$real->id_user');<br>";
            }

            echo "<br>";
        }

        $id_pinj = implode(', ', $id_pinj);
        echo "DELETE FROM rencana_angsuran_$lokasi WHERE loan_id IN ($id_pinj); <br><br>";
        echo "DELETE FROM real_angsuran_$lokasi WHERE loan_id IN ($id_pinj); <br><br>";
        echo "================================================================";
    }
}
