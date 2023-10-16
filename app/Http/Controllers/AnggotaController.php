<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Keluarga;
use App\Models\PinjamanAnggota;
use App\Models\Usaha;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $penduduk = Anggota::with([
                'pinjaman' => function ($query) {
                    $query->orderBy('id', 'DESC');
                },
                'pinjaman.sts'
            ])->withCount('pinjaman')->orderBy('id', 'DESC')->get();

            return DataTables::of($penduduk)
                ->addColumn('status', function ($row) {
                    $pinjaman = $row->pinjaman;

                    $status = '<span class="badge badge-secondary">n</span>';
                    if ($row->pinjaman_count > 0) {
                        $status_pinjaman = $pinjaman->status;

                        $badge = $pinjaman->sts->warna_status;
                        $status = '<span class="badge badge-' . $badge . '">' . $status_pinjaman . '</span>';
                    }

                    return $status;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $title = 'Daftar Penduduk';
        return view('penduduk.index')->with(compact('title'));
    }

    public function register()
    {
        $title = 'Register Penduduk';
        return view('penduduk.register')->with(compact('title'));
    }

    public function cariNik()
    {
        $nik = request()->get('nik');
        $anggota = Anggota::where('nik', $nik)->first();

        $anggota->tgl_lahir = Tanggal::tglIndo($anggota->tgl_lahir);
        return response()->json($anggota);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $desa = Desa::where('kd_kec', $kec->kd_kec)->with('sebutan_desa')->get();
        $jenis_usaha = Usaha::orderBy('nama_usaha', 'ASC')->get();
        $hubungan = Keluarga::orderBy('kekeluargaan', 'ASC')->get();

        $desa_dipilih = 0;
        $jenis_usaha_dipilih = 0;
        $hubungan_dipilih = 0;
        $jk_dipilih = 0;

        $nik = '';
        $value_tanggal = '';
        if (request()->get('nik')) {
            $anggota = Anggota::where('nik', request()->get('nik'));
            if ($anggota->count() > 0) {
                $data_anggota = $anggota->first();

                $desa_dipilih = $data_anggota->desa;
                $jenis_usaha_dipilih = $data_anggota->usaha;
                $hubungan_dipilih = $data_anggota->hubungan;
                $jk_dipilih = $data_anggota->jk;

                $data_anggota->tgl_lahir = Tanggal::tglIndo($data_anggota->tgl_lahir);
                return view('penduduk.edit')->with(compact('desa_dipilih', 'desa', 'jenis_usaha', 'jenis_usaha_dipilih', 'hubungan', 'hubungan_dipilih', 'jk_dipilih', 'data_anggota'));
            }

            $nik = request()->get('nik');
            $kk = substr($nik, 0, 6);
            $tanggal = substr($nik, 6, 2);
            $bulan = substr($nik, 8, 2);
            $tahun = substr($nik, 10, 2);
            if ($tanggal >= 40) {
                $tgl = $tanggal - 40;
                $jk_dipilih = 'P';
            } else {
                $tgl = $tanggal;
                $jk_dipilih = 'L';
            }
            if ($tgl < 10) {
                $tgl = "$tgl";
            }
            if ($tahun < 20) {
                $thn = "20$tahun";
            } else {
                $thn = "19$tahun";
            }

            $value_tanggal = Tanggal::tglIndo("$thn-$bulan-$tgl");
        }

        return view('penduduk.create')->with(compact('desa_dipilih', 'desa', 'jenis_usaha', 'jenis_usaha_dipilih', 'hubungan', 'hubungan_dipilih', 'nik', 'jk_dipilih', 'value_tanggal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'nik',
            'nama_lengkap',
            'desa',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'no_telp',
            'alamat',
            'no_kk',
            'jenis_usaha',
            'nik_penjamin',
            'penjamin',
            'hubungan'
        ]);



        $rules = [
            'nik' => 'required|unique:anggota_' . auth()->user()->lokasi . ',nik|min:16|max:16',
            'nama_lengkap' => 'required',
            'desa' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
            'no_kk' => 'required',
            'jenis_usaha' => 'required',
            'nik_penjamin' => 'required|max:16',
            'penjamin' => 'required',
            'hubungan' => 'required'
        ];

        if (strlen($request->no_kk) >= 16) {
            $rules['no_kk'] = 'required|unique:anggota_' . auth()->user()->lokasi . ',kk';
        }

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $insert = [
            'nik' => $request->nik,
            'namadepan' => $request->nama_lengkap,
            'jk' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => Tanggal::tglNasional($request->tgl_lahir),
            'alamat' => $request->alamat,
            'desa' => $request->desa,
            'lokasi' => auth()->user()->lokasi,
            'hp' => $request->no_telp,
            'kk' => $request->no_kk,
            'nik_penjamin' => $request->nik_penjamin,
            'penjamin' => $request->penjamin,
            'hubungan' => $request->hubungan,
            'usaha' => $request->jenis_usaha,
            'foto' => '1',
            'terdaftar' => date('Y-m-d'),
            'status' => 'A',
            'petugas' => auth()->user()->id,
        ];

        $penduduk = Anggota::create($insert);
        return response()->json([
            'msg' => 'Penduduk dengan nama ' . $insert['namadepan'] . ' berhasil disimpan'
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anggota $penduduk)
    {
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $desa = Desa::where('kd_kec', $kec->kd_kec)->with('sebutan_desa')->get();
        $jenis_usaha = Usaha::orderBy('nama_usaha', 'ASC')->get();
        $hubungan = Keluarga::orderBy('kekeluargaan', 'ASC')->get();

        $penduduk = $penduduk->with([
            'pinjaman_anggota',
            'pinjaman_anggota.kelompok',
            'pinjaman_anggota.pinkel',
            'pinjaman_anggota.pinkel.sts',
            'pinjaman_anggota.pinkel.angsuran_pokok',
        ])->where('id', $penduduk->id)->first();
        $desa_dipilih = $penduduk->desa;
        $jenis_usaha_dipilih = $penduduk->usaha;
        $hubungan_dipilih = $penduduk->hubungan;
        $jk_dipilih = $penduduk->jk;
        $penduduk->tgl_lahir = Tanggal::tglIndo($penduduk->tgl_lahir);

        $title = ucwords($penduduk->namadepan);
        return view('penduduk.detail')->with(compact('penduduk', 'title', 'desa_dipilih', 'desa', 'jenis_usaha', 'jenis_usaha_dipilih', 'hubungan', 'hubungan_dipilih', 'jk_dipilih'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $penduduk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anggota $penduduk)
    {
        $data = $request->only([
            'nik',
            'nama_lengkap',
            'desa',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'no_telp',
            'alamat',
            'no_kk',
            'jenis_usaha',
            'nik_penjamin',
            'penjamin',
            'hubungan'
        ]);

        $rules = [
            'nama_lengkap' => 'required',
            'desa' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
            'no_kk' => 'required',
            'jenis_usaha' => 'required',
            'nik_penjamin' => 'required|max:16',
            'penjamin' => 'required',
            'hubungan' => 'required'
        ];

        if ($request->nik != $penduduk->nik) {
            $rules['nik'] = 'required|unique:anggota_' . auth()->user()->lokasi . ',nik|min:16|max:16';
        }

        if (strlen($request->no_kk) >= 16) {
            $rules['no_kk'] = 'required';
            if ($request->no_kk != $penduduk->kk) {
                $rules['no_kk'] = 'required|unique:anggota_' . auth()->user()->lokasi . ',kk';
            }
        }

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $update = [
            'nik' => $request->nik,
            'namadepan' => $request->nama_lengkap,
            'jk' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => Tanggal::tglNasional($request->tgl_lahir),
            'alamat' => $request->alamat,
            'desa' => $request->desa,
            'lokasi' => auth()->user()->lokasi,
            'hp' => $request->no_telp,
            'kk' => $request->no_kk,
            'nik_penjamin' => $request->nik_penjamin,
            'penjamin' => $request->penjamin,
            'hubungan' => $request->hubungan,
            'usaha' => $request->jenis_usaha,
            'foto' => '1',
            'terdaftar' => date('Y-m-d'),
            'status' => 'A',
            'petugas' => auth()->user()->id,
        ];

        $pend = Anggota::where('nik', $penduduk->nik)->update($update);
        return response()->json([
            'msg' => 'Penduduk dengan nama ' . $update['namadepan'] . ' berhasil disimpan'
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $penduduk)
    {
        //
    }
}
