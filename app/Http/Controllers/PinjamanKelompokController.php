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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
            $pinkel = PinjamanKelompok::where('status', 'A')
                ->whereRaw('alokasi<=(SELECT SUM(realisasi_pokok) FROM real_angsuran_' . auth()->user()->lokasi . ' WHERE loan_id=id)')
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
        $title = 'Registrasi Pinjaman';
        return view('pinjaman.create')->with(compact('title'));
    }

    public function DaftarKelompok()
    {
        $kelompok = Kelompok::with('d')->get();
        return view('pinjaman.partials.kelompok')->with(compact('kelompok'));
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

            $pinjaman_anggota = PinjamanAnggota::where('id_pinkel', $perguliran->id)->with('anggota')->get();
            $pinj_a['jumlah_pinjaman'] = 0;
            $pinj_a['jumlah_pemanfaat'] = 0;
            $pinj_a['jumlah_kelompok'] = 0;

            foreach ($pinjaman_anggota as $pa) {
                $pinj_aktif = PinjamanAnggota::where('nia', $pa->nia)->where('status', 'A');

                if ($pinj_aktif->count() > 0) {
                    $pinj_a['jumlah_pinjaman'] += 1;
                    $pinj_a['pinjaman'][] = $pinj_aktif->with('pinkel', 'kelompok', 'anggota')->first();
                }

                $pemanfaat_aktif = DataPemanfaat::where([['nik', $pa->nik], ['status', 'A']])->with('kecamatan');
                if ($pemanfaat_aktif->count() > 0) {
                    $pinj_a['jumlah_pemanfaat'] += 1;
                    $pinj_a['pemanfaat'][$pa->nik] = $pemanfaat_aktif->first();
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
        return view('perguliran.detail')->with(compact('title', 'perguliran'));
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

    public function generate($id_pinj)
    {
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
        }

        return response()->json([
            'success' => true,
            'ra' => $ra
        ], Response::HTTP_OK);
    }
}
