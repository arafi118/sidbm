<?php

namespace App\Http\Controllers;

use App\Models\DataPemanfaat;
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
use App\Utils\Tanggal;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DNS1D;
use Illuminate\Support\Facades\Http;

class PinjamanKelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Tahapan Perguliran';
        return view('perguliran.index')->with(compact('title'));
    }

    public function proposal()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where('status', 'P')->with('kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();

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

                    $nama_kelompok = $row->kelompok->nama_kelompok . '(' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                ->editColumn('tgl_proposal', function ($row) {
                    return Tanggal::tglIndo($row->tgl_proposal);
                })
                ->editColumn('proposal', function ($row) {
                    return number_format($row->proposal);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function verified()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where('status', 'V')->with('kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();

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

                    $nama_kelompok = $row->kelompok->nama_kelompok . '(' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                ->editColumn('tgl_verifikasi', function ($row) {
                    return Tanggal::tglIndo($row->tgl_verifikasi);
                })
                ->editColumn('verifikasi', function ($row) {
                    return number_format($row->verifikasi);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function waiting()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where('status', 'W')->with('kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();

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

                    $nama_kelompok = $row->kelompok->nama_kelompok . '(' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                ->editColumn('tgl_tunggu', function ($row) {
                    return Tanggal::tglIndo($row->tgl_tunggu);
                })
                ->editColumn('alokasi', function ($row) {
                    return number_format($row->alokasi);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function aktif()
    {
        if (request()->ajax()) {
            $pinkel = PinjamanKelompok::where('status', 'A')->with('kelompok', 'jpp', 'sts')->withCount('pinjaman_anggota')->get();

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

                    $nama_kelompok = $row->kelompok->nama_kelompok . '(' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                ->editColumn('tgl_cair', function ($row) {
                    return Tanggal::tglIndo($row->tgl_cair);
                })
                ->editColumn('alokasi', function ($row) {
                    return number_format($row->alokasi);
                })
                ->rawColumns(['nama_kelompok'])
                ->make(true);
        }
    }

    public function lunas()
    {
        if (request()->ajax()) {
            $tb_pinkel = 'pinjaman_kelompok_' . auth()->user()->lokasi;
            $pinkel = PinjamanKelompok::where('status', 'A')
                ->whereRaw($tb_pinkel . '.alokasi<=(SELECT SUM(realisasi_pokok) FROM real_angsuran_' . auth()->user()->lokasi . ' WHERE loan_id=' . $tb_pinkel . '.id)')
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

                    $nama_kelompok = $row->kelompok->nama_kelompok . '(' . $jpp->nama_jpp . ')';
                    return '<div>' . $nama_kelompok . ' <small class="float-end badge badge-' . $status . '">Loan ID.' . $row->id . '</small></div>';
                })
                ->editColumn('tgl_cair', function ($row) {
                    return Tanggal::tglIndo($row->tgl_cair);
                })
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
        $id_kel = request()->get('id_kel');
        $kelompok = Kelompok::with([
            'd',
            'pinjaman' => function ($query) {
                $query->orderBy('tgl_proposal', 'DESC');
            }
        ])->withCount('pinjaman')->get();

        return view('pinjaman.partials.kelompok')->with(compact('kelompok', 'id_kel'));
    }

    public function register($id_kel)
    {
        $kelompok = Kelompok::where('id', $id_kel)->first();
        $kec = Kecamatan::where('id', auth()->user()->id)->first();
        $jenis_jasa = JenisJasa::all();
        $sistem_angsuran = SistemAngsuran::all();
        $jenis_pp = JenisProdukPinjaman::where('lokasi', '0')->get();

        $jenis_pp_dipilih = $kelompok->jenis_produk_pinjaman;
        return view('pinjaman.partials.register')->with(compact('kelompok', 'kec', 'jenis_jasa', 'sistem_angsuran', 'jenis_pp', 'jenis_pp_dipilih'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kel = Kelompok::where('id', $request->id_kel)->first();
        $data = $request->only([
            'tgl_proposal',
            'pengajuan',
            'jangka',
            'pros_jasa',
            'jenis_jasa',
            'sistem_angsuran_pokok',
            'sistem_angsuran_jasa',
            'jenis_produk_pinjaman'
        ]);

        $validate = Validator::make($data, [
            'tgl_proposal' => 'required',
            'pengajuan' => 'required',
            'jangka' => 'required',
            'pros_jasa' => 'required',
            'jenis_jasa' => 'required',
            'sistem_angsuran_pokok' => 'required',
            'sistem_angsuran_jasa' => 'required',
            'jenis_produk_pinjaman' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $insert = [
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
            'wt_cair' => '0',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ];

        $pinjaman_kelompok = PinjamanKelompok::create($insert);

        return response()->json([
            'msg' => 'Proposal Pinjaman Kelompok ' . $kel->nama_kelompok . ' berhasil dibuat',
            'kode_kelompok' => $kel->kd_kelompok + 1,
            'desa' => $kel->desa
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
                $query->where('status', 'A');
            },
            'pinjaman_anggota.anggota.pemanfaat.kec',
            'pinjaman_anggota.pinjaman' => function ($query) {
                $query->where('status', 'A');
            },
            'pinjaman_anggota.pinjaman.pinkel',
            'pinjaman_anggota.pinjaman.kelompok',
            'pinjaman_anggota.pinjaman.anggota'
        ])->where('id', $perguliran->id)->first();
        $jenis_jasa = JenisJasa::all();
        $sistem_angsuran = SistemAngsuran::all();
        $sumber_bayar = Rekening::where([
            ['lev1', '1'],
            ['lev2', '1'],
            ['lev3', '1'],
            ['lev4', '!=', '2']
        ])->orderBy('kode_akun', 'asc')->get();
        $debet = Rekening::where([
            ['lev1', '1'],
            ['lev2', '1'],
            ['lev3', '3'],
            ['lev4', $perguliran->jenis_pp]
        ])->first();

        if ($perguliran->status == 'A') {
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

            foreach ($pinjaman_anggota as $pa) {
                $pinj_aktif = $pa->pinjaman;

                if ($pinj_aktif) {
                    $pinj_a['jumlah_pinjaman'] += 1;
                    $pinj_a['pinjaman'][] = $pinj_aktif;
                }

                $pemanfaat_aktif = $pa->anggota->pemanfaat;
                if ($pemanfaat_aktif) {
                    $pinj_a['jumlah_pemanfaat'] += 1;
                    $pinj_a['pemanfaat'][$pa->anggota->nik] = $pemanfaat_aktif;
                }
            }

            $pinjaman_kelompok = PinjamanKelompok::where('id_kel', $perguliran->id_kel)->where('status', 'A')->with('kelompok')->get();
            foreach ($pinjaman_kelompok as $pinkel) {
                $pinj_a['jumlah_kelompok'] += 1;
                $pinj_a['kelompok'][] = $pinkel;
            }
        }

        return view('perguliran.partials/' . $view)->with(compact('perguliran', 'jenis_jasa', 'sistem_angsuran', 'sumber_bayar', 'debet', 'pinj_a'));
    }

    public function detail(PinjamanKelompok $perguliran)
    {
        $title = 'Detal Pinjaman Kelompok ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $sistem_angsuran = SistemAngsuran::all();
        return view('perguliran.detail')->with(compact('title', 'perguliran', 'real', 'sistem_angsuran'));
    }

    public function pelunasan(PinjamanKelompok $perguliran)
    {
        $title = 'Detal Pinjaman Kelompok ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $ra = RencanaAngsuran::where('loan_id', $perguliran->id)->orderBy('jatuh_tempo', 'DESC')->first();
        return view('perguliran.partials.lunas')->with(compact('title', 'perguliran', 'real', 'ra'));
    }

    public function keterangan(PinjamanKelompok $perguliran)
    {
        $title = 'Cetak Keterangan Pelunasan ' . $perguliran->kelompok->nama_kelompok;
        $real = RealAngsuran::where('loan_id', $perguliran->id)->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC')->first();
        $ra = RencanaAngsuran::where('loan_id', $perguliran->id)->orderBy('jatuh_tempo', 'DESC')->first();
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $dir = User::where([
            ['lokasi', auth()->user()->lokasi],
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

            DataPemanfaat::where('id_pinkel', $perguliran->id)->where('lokasi', auth()->user()->lokasi)->update([
                'status' => 'L'
            ]);

            PinjamanKelompok::where('id', $perguliran->id)->update([
                'status' => 'L',
                'tgl_lunas' => date('Y-m-d')
            ]);

            return response()->json([
                'msg' => 'Validasi Pelunasan Pinjaman Kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil.',
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
                'catatan_verifikasi'
            ]);

            $validate = Validator::make($data, [
                $tgl => 'required',
                $alokasi => 'required',
                'jangka_proposal' => 'required',
                'pros_jasa_proposal' => 'required',
                'jenis_jasa_proposal' => 'required',
                'sistem_angsuran_pokok_proposal' => 'required',
                'sistem_angsuran_jasa_proposal' => 'required',
                'catatan_verifikasi' => 'required'
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

            $table = 'pinjaman_kelompok_' . auth()->user()->lokasi;
            $validate = Validator::make($data, [
                $tgl => 'required',
                $alokasi => 'required',
                'jangka' => 'required',
                'pros_jasa' => 'required',
                'jenis_jasa' => 'required',
                'sistem_angsuran_pokok' => 'required',
                'sistem_angsuran_jasa' => 'required',
                'tgl_cair' => 'required',
                'nomor_spk' => 'required|unique:' . $table . ',spk_no'
            ]);
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
            $update = [
                $tgl => Tanggal::tglNasional($data[$tgl]),
                $alokasi => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'status' => 'A'
            ];

            PinjamanAnggota::where('id_pinkel', $perguliran->id)->update([
                'status' => 'A'
            ]);

            DataPemanfaat::where([['id_pinkel', $perguliran->id], ['lokasi', auth()->user()->lokasi]])->update([
                'status' => 'A'
            ]);

            $keterangan = 'Pencairan Kel. ' . $perguliran->kelompok->nama_kelompok;
            $keterangan .= ' (' . $perguliran->jpp->nama_jpp . ')';

            Transaksi::create([
                'tgl_transaksi' => Tanggal::tglNasional($data[$tgl]),
                'rekening_debit' => $request->debet,
                'rekening_kredit' => $request->sumber_pembayaran,
                'idtp' => '0',
                'id_pinj' => $perguliran->id,
                'id_pinj_i' => '0',
                'keterangan_transaksi' => $keterangan,
                'relasi' => $perguliran->kelompok->nama_kelompok,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'urutan' => '0',
                'id_user' => auth()->user()->id,
            ]);
        } elseif ($request->status == 'W') {
            if ($request->idpa != null) {
                foreach ($request->idpa as $idpa => $val) {

                    PinjamanAnggota::where('id', $idpa)->update([
                        $tgl => Tanggal::tglNasional($data[$tgl]),
                        $alokasi => str_replace(',', '', str_replace('.00', '', $val)),
                        'status' => $data['status']
                    ]);

                    DataPemanfaat::where([['idpa', $idpa], ['lokasi', auth()->user()->lokasi]])->update([
                        'status' => $data['status']
                    ]);
                }
            }

            $update = [
                'tgl_dana' => Tanggal::tglNasional($data[$tgl]),
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
                foreach ($request->idpa as $idpa => $val) {
                    $catatan = $request->catatan[$idpa];

                    PinjamanAnggota::where('id', $idpa)->update([
                        $tgl => Tanggal::tglNasional($data[$tgl]),
                        $alokasi => str_replace(',', '', str_replace('.00', '', $val)),
                        'catatan_verifikasi' => $catatan,
                        'status' => $data['status']
                    ]);

                    DataPemanfaat::where([['idpa', $idpa], ['lokasi', auth()->user()->lokasi]])->update([
                        'status' => $data['status']
                    ]);
                }
            }

            $update = [
                $tgl => Tanggal::tglNasional($data[$tgl]),
                $alokasi => str_replace(',', '', str_replace('.00', '', $data[$alokasi])),
                'jangka' => $data['jangka'],
                'pros_jasa' => $data['pros_jasa'],
                'jenis_jasa' => $data['jenis_jasa'],
                'sistem_angsuran' => $data['sistem_angsuran_pokok'],
                'sa_jasa' => $data['sistem_angsuran_jasa'],
                'catatan_verifikasi' => $data['catatan_verifikasi'],
                'status' => $data['status']
            ];
        }

        $pinkel = PinjamanKelompok::where('id', $perguliran->id)->update($update);

        if ($request->status == 'W' || $request->status == 'A') {
            $this->generate($perguliran->id);
        }

        if ($perguliran->status == 'P') {
            $msg = 'Rekom Verifikator berhasil disimpan';
        } elseif ($perguliran->status == 'V') {
            $msg = 'Keputusan Pendanaan berhasil disimpan';
        } elseif ($perguliran->status == 'W') {
            $msg = 'Proposal Kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil dicairkan';
        } elseif ($perguliran->status == '0') {
            $msg = 'Proposal berhasil diedit';
        }

        return response()->json([
            'msg' => $msg,
            'id' => $perguliran->id
        ], Response::HTTP_ACCEPTED);
    }

    public function simpan(Request $request, $id)
    {
        $data = $request->only([
            'spk_no',
            'tgl_cair',
            'waktu',
            'tempat'
        ]);

        $pinkel = PinjamanKelompok::where('id', $id)->with('kelompok')->first();

        $wt_cair = $data['waktu'] . '_' . $data['tempat'];
        $pinjaman = PinjamanKelompok::where('id', $id)->update([
            'spk_no' => $data['spk_no'],
            'tgl_cair' => Tanggal::tglNasional($data['tgl_cair']),
            'wt_cair' => $wt_cair
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Pinjaman Kelompok ' . $pinkel->kelompok->nama_kelompok . ' Berhasil Diperbarui',
            'tgl_cair' => $data['tgl_cair']
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
            ['lokasi', auth()->user()->lokasi]
        ])->update([
            'status' => 'P'
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Pinjaman Kelompok ' . $id->kelompok->nama_kelompok . ' Loan ID. ' . $id->id . ' berhasil dikembalikan menjadi status P (Pengajuan/Proposal)',
            'id_pinkel' => $id->id
        ]);
    }

    public function rescedule(Request $request)
    {
        $id = $request->id;
        $tgl_resceduling = $request->tgl_resceduling;
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
            'pinjaman_anggota'
        ])->withCount('pinjaman_anggota')->first();

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
            'tgl_transaksi' => Tanggal::tglNasional($tgl_resceduling),
            'rekening_debit' => $rekening_2,
            'rekening_kredit' => $rekening_1,
            'idtp' => $last_idtp + 1,
            'id_pinj' => $pinkel->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => 'Angs. Resc. ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')',
            'relasi' => $pinkel->kelompok->nama_kelompok,
            'jumlah' => $pengajuan,
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        $update_pinkel = PinjamanKelompok::where('id', $id)->update([
            'tgl_lunas' => Tanggal::tglNasional($tgl_resceduling),
            'status' => 'R',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ]);

        $update_pinj_a = PinjamanAnggota::where('id_pinkel', $id)->update([
            'tgl_lunas' => Tanggal::tglNasional($tgl_resceduling),
            'status' => 'R',
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id
        ]);

        $pinjaman = PinjamanKelompok::create([
            'id_kel' => $pinkel->id_kel,
            'jenis_pp' => $pinkel->jenis_pp,
            'tgl_proposal' => Tanggal::tglNasional($tgl_resceduling),
            'tgl_verifikasi' => Tanggal::tglNasional($tgl_resceduling),
            'tgl_dana' => Tanggal::tglNasional($tgl_resceduling),
            'tgl_tunggu' => Tanggal::tglNasional($tgl_resceduling),
            'tgl_cair' => Tanggal::tglNasional($tgl_resceduling),
            'tgl_lunas' => Tanggal::tglNasional($tgl_resceduling),
            'proposal' => $pengajuan,
            'verifikasi' => $pengajuan,
            'alokasi' => $pengajuan,
            'spk_no' => $request->get('spk'),
            'sumber' => $pinkel->sumber,
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
            'tgl_transaksi' => Tanggal::tglNasional($tgl_resceduling),
            'rekening_debit' => $rekening_1,
            'rekening_kredit' => $rekening_2,
            'idtp' => '0',
            'id_pinj' => $pinjaman->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => 'Pencairan Resc ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinjaman->id . ')',
            'relasi' => $pinkel->kelompok->nama_kelompok,
            'jumlah' => $pengajuan,
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        foreach ($pinkel->pinjaman_anggota as $pa) {
            $pinjaman_anggota = [
                'jenis_pinjaman' => $pa->jenis_pinjaman,
                'id_kel' => $pa->id_kel,
                'id_pinkel' => $pinjaman->id,
                'jenis_pp' => $pa->jenis_pp,
                'nia' => $pa->nia,
                'tgl_proposal' => Tanggal::tglNasional($tgl_resceduling),
                'tgl_verifikasi' => Tanggal::tglNasional($tgl_resceduling),
                'tgl_dana' => Tanggal::tglNasional($tgl_resceduling),
                'tgl_tunggu' => Tanggal::tglNasional($tgl_resceduling),
                'tgl_cair' => Tanggal::tglNasional($tgl_resceduling),
                'tgl_lunas' => Tanggal::tglNasional($tgl_resceduling),
                'proposal' => $pengajuan / $pinkel->pinjaman_anggota_count,
                'verifikasi' => $pengajuan / $pinkel->pinjaman_anggota_count,
                'alokasi' => $pengajuan / $pinkel->pinjaman_anggota_count,
                'kom_pokok' => $pa->kom_pokok,
                'kom_jasa' => $pa->kom_jasa,
                'spk_no' => $pinjaman->spk_no,
                'sumber' => $pa->sumber,
                'pros_jasa' => $pros_jasa,
                'jenis_jasa' => $pa->jenis_jasa,
                'jangka' => $jangka,
                'sistem_angsuran' => $sis_pokok,
                'sa_jasa' => $sis_jasa,
                'status' => 'A',
                'catatan_verifikasi' => $pinjaman->catatan_verifikasi,
                'lu' => $pinjaman->lu,
                'user_id' => $pinjaman->user_id,
            ];

            $pinj_a = PinjamanAnggota::create($pinjaman_anggota);
        }


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
            'tgl_transaksi' => Tanggal::tglNasional($data['tgl_penghapusan']),
            'rekening_debit' => $rekening_debit,
            'rekening_kredit' => $rekening_kredit,
            'idtp' => $last_idtp + 1,
            'id_pinj' => $pinkel->id,
            'id_pinj_i' => '0',
            'keterangan_transaksi' => 'Penghapusan Pinjaman Kelompok ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')',
            'relasi' => $pinkel->kelompok->nama_kelompok,
            'jumlah' => $data['saldo'],
            'urutan' => '0',
            'id_user' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Penghapusan Pinjaman Kelompok ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ') berhasil',
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

            return response()->json([
                'hapus' => true,
                'msg' => 'Proposal pinjaman kelompok ' . $perguliran->kelompok->nama_kelompok . ' berhasil dihapus'
            ]);
        }

        return response()->json([
            'hapus' => false,
            'msg' => 'Proposal pinjaman kelompok ' . $perguliran->kelompok->nama_kelompok . ' gagal dihapus'
        ]);
    }

    public function cariKelompok()
    {
        $param = request()->get('query');
        if (strlen($param) >= '0') {
            $kelompok = Kelompok::leftJoin('desa', 'desa.kd_desa', '=', 'kelompok_' . auth()->user()->lokasi . '.desa')
                ->leftJoin('pinjaman_kelompok_' . auth()->user()->lokasi . ' as pk', 'pk.id_kel', '=', 'kelompok_' . auth()->user()->lokasi . '.id')
                ->where(function ($query) use ($param) {
                    $query->where('kelompok_' . auth()->user()->lokasi . '.nama_kelompok', 'like', '%' . $param . '%')
                        ->orwhere('kelompok_' . auth()->user()->lokasi . '.kd_kelompok', 'like', '%' . $param . '%')
                        ->orwhere('kelompok_' . auth()->user()->lokasi . '.ketua', 'like', '%' . $param . '%');
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

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten', 'kabupaten.wilayah', 'desa')->first();
        $kab = $kec->kabupaten;
        $dir = User::where([
            ['lokasi', auth()->user()->lokasi],
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

        $file = $request->report;
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function suratPengajuanPinjaman($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa'
        ])->withCount('pinjaman_anggota')->first();

        $data['judul'] = 'Surat Perngajuran Kredit (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pengajuan_kredit', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function anggotaKelompok($id, $data)
    {
        $data['pinjaman'] = PinjamanAnggota::where('id_pinkel', $id)->with([
            'anggota',
            'anggota.d'
        ])->get();

        $data['judul'] = 'Daftar Anggota (Loan ID. ' . $id . ')';
        $view = view('perguliran.dokumen.anggota', $data)->render();
        $pdf = PDF::loadHTML($view);

        return $pdf->stream();
    }

    public function daftarPemanfaat($id, $data)
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['judul'] = 'Daftar Pemanfaat (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pemanfaat', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function  tanggungRenteng($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Pernyataan Tanggung Renteng (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanggung_renteng', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function fotoCopyKTP($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
        ])->first();

        $data['judul'] = 'FC KTP Pemanfaat dan Penjamin (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.ktp', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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

        $data['judul'] = 'Pernyataan Peminjam (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pernyataan_peminjam', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            'sis_pokok',
            'user'
        ])->first();

        $data['user'] = User::where([
            ['lokasi', auth()->user()->lokasi],
            ['level', '4']
        ])->with('j')->orderBy('id')->get();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Form Verifikasi (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.form_verifikasi', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function rencanaAngsuran($id, $data)
    {
        $data['rencana'] = $this->generate($id)->getData()->rencana;
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'jpp',
            'kelompok',
            'sis_pokok',
            'jasa'
        ])->first();

        $data['dir'] = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['judul'] = 'Rencana Angsuran (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.rencana_angsuran', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['transaksi'] = Transaksi::where('id_pinj', $id)->orderBy('tgl_transaksi', 'ASC')->with('user')->orderBy('idtp', 'ASC')->get();

        $data['judul'] = 'Rekening Koran (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.rekening_koran', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function iptw($id, $data)
    {
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota'
        ])->first();

        $data['judul'] = 'Daftar Penerima IPTW (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.iptw', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Perjanjian Kredit (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.spk', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Kelayakan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.surat_kelayakan', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi],
            ['level', '4']
        ])->with('j')->orderBy('id')->get();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Berita Acara Pencairan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.ba_pencairan', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['judul'] = 'Tanda Terima (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanda_terima', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function kartuAngsuran($id)
    {
        $data['kec'] = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
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

        $data['dir'] = User::where([
            ['lokasi', auth()->user()->lokasi],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $data['laporan'] = 'Kartu Angsuran ' . $data['pinkel']->kelompok->nama_kelompok;
        return view('perguliran.dokumen.kartu_angsuran', $data);
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['judul'] = 'Pemberitahuan Ke Desa (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pemberitahuan_desa', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Tanggung Renteng Kematian (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tanggung_renteng_kematian', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function pernyataanTanggungRenteng($id, $data)
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
        ])->first();

        $data['judul'] = 'Pernyataan Tanggung Renteng (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.pernyataan_tanggung_renteng', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['skr'] = User::where([
            ['level', '1'],
            ['jabatan', '2'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['bp'] = User::where([
            ['level', '3'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Kuitansi Pencairan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.kuitansi', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
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
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $data['keuangan'] = $keuangan;

        $data['judul'] = 'Surat Tagihan (' . $data['pinkel']->kelompok->nama_kelompok . ' - Loan ID. ' . $data['pinkel']->id . ')';
        $view = view('perguliran.dokumen.tagihan', $data)->render();
        $pdf = PDF::loadHTML($view);
        return $pdf->stream();
    }

    public function cetakPadaKartu($id, $idtp)
    {
        $data['kec'] = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
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
            ['lokasi', auth()->user()->lokasi],
            ['level', '1'],
            ['jabatan', '1']
        ])->first();

        $data['laporan'] = 'Kartu Angsuran ' . $data['pinkel']->kelompok->nama_kelompok;
        return view('perguliran.dokumen.cetak_kartu_angsuran', $data);
    }

    public function generate($id_pinj)
    {
        $rencana = [];
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
            $wajib_pokok = Keuangan::bulatkan($alokasi / $tempo_pokok);
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
            $wajib_jasa = Keuangan::bulatkan($sum_jasa / $tempo_jasa);

            if ($sisa == 0) {
                $angsuran_jasa = $wajib_jasa;
            } else {
                $angsuran_jasa = 0;
            }

            $ra[$j]['jasa'] = $angsuran_jasa;
        }
        $ra['alokasi'] = $alokasi;

        if (request()->get('save')) {
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
}
