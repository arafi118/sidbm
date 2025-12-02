<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Kelompok;
use App\Models\PinjamanAnggota;
use Illuminate\Support\Facades\DB;

class BasisDataController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::where('id', request()->user()->lokasi)->first();

        $lokasi = $kecamatan->id;
        $stats = DB::table('desa')->selectRaw("
            (SELECT COUNT(*) FROM desa WHERE kd_kec = ?) as jumlah_desa,
            (SELECT COUNT(*) FROM anggota_{$lokasi}) as jumlah_anggota,
            (SELECT COUNT(*) FROM kelompok_{$lokasi} WHERE jenis_produk_pinjaman != 3) as jumlah_kelompok,
            (SELECT COUNT(*) FROM kelompok_{$lokasi} WHERE jenis_produk_pinjaman = 3) as jumlah_lembaga_lain
        ", [$kecamatan->kd_kec])->first();

        $jumlahDesa = $stats->jumlah_desa;
        $jumlahAnggota = $stats->jumlah_anggota;
        $jumlahKelompok = $stats->jumlah_kelompok;
        $jumlahLembagaLain = $stats->jumlah_lembaga_lain;

        return response()->json([
            'success' => true,
            'data' => [
                [
                    'nama' => 'Desa',
                    'jumlah' => $jumlahDesa,
                ],
                [
                    'nama' => 'Penduduk',
                    'jumlah' => $jumlahAnggota,
                ],
                [
                    'nama' => 'Kelompok',
                    'jumlah' => $jumlahKelompok,
                ],
                [
                    'nama' => 'Lembaga Lain',
                    'jumlah' => $jumlahLembagaLain,
                ],
            ],
        ]);
    }

    public function desa()
    {
        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $kecamatan = Kecamatan::where('id', request()->user()->lokasi)->first();

        $query = Desa::query()
            ->select('desa.*', 'sebutan_desa.sebutan_desa')
            ->join('sebutan_desa', 'desa.sebutan', '=', 'sebutan_desa.id')
            ->where('desa.kd_kec', $kecamatan->kd_kec)
            ->where(function ($query) use ($search) {
                $query->where('desa.nama_desa', 'like', '%'.$search.'%')
                    ->orWhere('desa.kades', 'like', '%'.$search.'%')
                    ->orWhere('desa.kd_desa', 'like', '%'.$search.'%');
            });

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function penduduk()
    {
        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_anggota = 'anggota_'.request()->user()->lokasi;

        $query = Anggota::from($tb_anggota.' as anggota')
            ->select('anggota.*', 'desa.nama_desa', 'desa.kd_desa', 'sebutan_desa.sebutan_desa')
            ->join('desa', 'anggota.desa', '=', 'desa.kd_desa')
            ->join('sebutan_desa', 'desa.sebutan', '=', 'sebutan_desa.id')
            ->with([
                'pinjaman' => function ($query) {
                    $query->orderBy('id', 'DESC');
                },
            ])
            ->where(function ($query) use ($search) {
                $query->where('anggota.namadepan', 'like', '%'.$search.'%')
                    ->orWhere('anggota.nik', 'like', '%'.$search.'%')
                    ->orWhere('desa.nama_desa', 'like', '%'.$search.'%');
            });

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function detailPenduduk($id)
    {
        $user = request()->user();

        $tb_pinjaman = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kelompok = 'kelompok_'.$user->lokasi;

        $pinjaman = PinjamanAnggota::from($tb_pinjaman.' as pinjaman')
            ->select(
                'pinjaman.id',
                'pinjaman.id_kel',
                'pinjaman.id_pinkel',
                'pinjaman.jenis_pp',
                'pinjaman.tgl_proposal',
                'pinjaman.tgl_verifikasi',
                'pinjaman.tgl_tunggu',
                'pinjaman.tgl_cair',
                'pinjaman.proposal',
                'pinjaman.verifikasi',
                'pinjaman.alokasi',
                'pinjaman.jenis_jasa',
                'pinjaman.pros_jasa',
                'pinjaman.jangka',
                'pinjaman.sistem_angsuran',
                'pinjaman.sa_jasa',
                'kelompok.nama_kelompok',
                'kelompok.alamat_kelompok',
                'kelompok.desa',
                'desa.nama_desa',
                'jenis_produk_pinjaman.nama_jpp',
                'jenis_jasa.nama_jj',
            )
            ->join('jenis_produk_pinjaman', 'pinjaman.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join('jenis_jasa', 'pinjaman.jenis_jasa', '=', 'jenis_jasa.id')
            ->join($tb_kelompok.' as kelompok', 'pinjaman.id_kel', '=', 'kelompok.id')
            ->join('desa', 'kelompok.desa', '=', 'desa.kd_desa')
            ->join('sebutan_desa', 'desa.sebutan', '=', 'sebutan_desa.id')
            ->with([
                'sis_pokok',
                'sis_jasa',
            ])
            ->where('pinjaman.nia', $id)
            ->orderBy('pinjaman.tgl_proposal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pinjaman,
        ], 200);
    }

    public function kelompok()
    {
        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_kelompok = 'kelompok_'.request()->user()->lokasi;

        $query = Kelompok::from($tb_kelompok.' as kelompok')
            ->select('kelompok.*', 'desa.nama_desa', 'desa.kd_desa', 'sebutan_desa.sebutan_desa')
            ->join('desa', 'kelompok.desa', '=', 'desa.kd_desa')
            ->join('sebutan_desa', 'desa.sebutan', '=', 'sebutan_desa.id')
            ->with([
                'pinjaman' => function ($query) {
                    $query->orderBy('tgl_proposal', 'DESC');
                },
            ])
            ->where('kelompok.jenis_produk_pinjaman', '!=', 3)
            ->where(function ($query) use ($search) {
                $query->where('kelompok.nama_kelompok', 'like', '%'.$search.'%')
                    ->orWhere('kelompok.kd_kelompok', 'like', '%'.$search.'%')
                    ->orWhere('desa.nama_desa', 'like', '%'.$search.'%');
            });

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function lembagaLain()
    {
        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_kelompok = 'kelompok_'.request()->user()->lokasi;

        $query = Kelompok::from($tb_kelompok.' as kelompok')
            ->select('kelompok.*', 'desa.nama_desa', 'desa.kd_desa', 'sebutan_desa.sebutan_desa')
            ->join('desa', 'kelompok.desa', '=', 'desa.kd_desa')
            ->join('sebutan_desa', 'desa.sebutan', '=', 'sebutan_desa.id')
            ->with([
                'pinjaman' => function ($query) {
                    $query->orderBy('tgl_proposal', 'DESC');
                },
            ])
            ->where('kelompok.jenis_produk_pinjaman', '=', 3)
            ->where(function ($query) use ($search) {
                $query->where('kelompok.nama_kelompok', 'like', '%'.$search.'%')
                    ->orWhere('kelompok.kd_kelompok', 'like', '%'.$search.'%')
                    ->orWhere('desa.nama_desa', 'like', '%'.$search.'%');
            });

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
}
