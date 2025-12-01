<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;
use App\Models\Rekening;
use App\Utils\Tanggal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $charts = $this->_saldo(date('Y-m-d'));

        return response()->json([
            'success' => true,
            'data' => $charts,
        ], 200);
    }

    public function proposal()
    {
        $user = request()->user();

        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_pinj = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
            DB::raw('COUNT(pa.id) as jumlah_anggota')
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->leftJoin($tb_pinj.' as pa', 'pa.id_pinkel', '=', 'pk.id')
            ->where('pk.status', 'P')
            ->where(function ($query) use ($search) {
                $query->where('k.nama_kelompok', 'LIKE', '%'.$search.'%')
                    ->orWhere('jenis_produk_pinjaman.nama_jpp', 'LIKE', '%'.$search.'%')
                    ->orWhere('pk.id', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('pk.id', 'k.nama_kelompok', 'k.alamat_kelompok', 'k.desa', 'desa.nama_desa', 'jenis_produk_pinjaman.nama_jpp');

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function verifikasi()
    {
        $user = request()->user();

        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_pinj = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
            DB::raw('COUNT(pa.id) as jumlah_anggota')
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->leftJoin($tb_pinj.' as pa', 'pa.id_pinkel', '=', 'pk.id')
            ->where('pk.status', 'V')
            ->where(function ($query) use ($search) {
                $query->where('k.nama_kelompok', 'LIKE', '%'.$search.'%')
                    ->orWhere('jenis_produk_pinjaman.nama_jpp', 'LIKE', '%'.$search.'%')
                    ->orWhere('pk.id', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('pk.id', 'k.nama_kelompok', 'k.alamat_kelompok', 'k.desa', 'desa.nama_desa', 'jenis_produk_pinjaman.nama_jpp');

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function waiting()
    {
        $user = request()->user();

        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_pinj = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
            DB::raw('COUNT(pa.id) as jumlah_anggota')
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->leftJoin($tb_pinj.' as pa', 'pa.id_pinkel', '=', 'pk.id')
            ->where('pk.status', 'W')
            ->where(function ($query) use ($search) {
                $query->where('k.nama_kelompok', 'LIKE', '%'.$search.'%')
                    ->orWhere('jenis_produk_pinjaman.nama_jpp', 'LIKE', '%'.$search.'%')
                    ->orWhere('pk.id', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('pk.id', 'k.nama_kelompok', 'k.alamat_kelompok', 'k.desa', 'desa.nama_desa', 'jenis_produk_pinjaman.nama_jpp');

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function aktif()
    {
        $user = request()->user();

        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_pinj = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
            DB::raw('COUNT(pa.id) as jumlah_anggota')
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->leftJoin($tb_pinj.' as pa', 'pa.id_pinkel', '=', 'pk.id')
            ->where('pk.status', 'A')
            ->where(function ($query) use ($search) {
                $query->where('k.nama_kelompok', 'LIKE', '%'.$search.'%')
                    ->orWhere('jenis_produk_pinjaman.nama_jpp', 'LIKE', '%'.$search.'%')
                    ->orWhere('pk.id', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('pk.id', 'k.nama_kelompok', 'k.alamat_kelompok', 'k.desa', 'desa.nama_desa', 'jenis_produk_pinjaman.nama_jpp');

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function lunas()
    {
        $user = request()->user();

        $page = request()->get('page') ?? 1;
        $perPage = request()->get('per_page') ?? 10;
        $sortBy = request()->get('sort_by') ?? 'id';
        $sortOrder = request()->get('sort_order') ?? 'asc';
        $search = request()->get('search') ?? '';

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_pinj = 'pinjaman_anggota_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;
        $tb_real = 'real_angsuran_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
            DB::raw('COUNT(pa.id) as jumlah_anggota')
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->leftJoin($tb_pinj.' as pa', 'pa.id_pinkel', '=', 'pk.id')
            ->where('pk.status', 'A')
            ->whereRaw('pk.alokasi<=(SELECT SUM(realisasi_pokok) FROM '.$tb_real.' WHERE '.$tb_real.'.loan_id='.'pk.id)')
            ->where(function ($query) use ($search) {
                $query->where('k.nama_kelompok', 'LIKE', '%'.$search.'%')
                    ->orWhere('jenis_produk_pinjaman.nama_jpp', 'LIKE', '%'.$search.'%')
                    ->orWhere('pk.id', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('pk.id', 'k.nama_kelompok', 'k.alamat_kelompok', 'k.desa', 'desa.nama_desa', 'jenis_produk_pinjaman.nama_jpp');

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function detailPinjaman($id)
    {
        $user = request()->user();

        $tb_pinkel = 'pinjaman_kelompok_'.$user->lokasi;
        $tb_kel = 'kelompok_'.$user->lokasi;

        $query = PinjamanKelompok::select(
            'pk.*',
            'k.nama_kelompok',
            'k.alamat_kelompok',
            'k.desa',
            'desa.nama_desa',
            'jenis_produk_pinjaman.nama_jpp',
        )->from($tb_pinkel.' as pk')
            ->join('jenis_produk_pinjaman', 'pk.jenis_pp', '=', 'jenis_produk_pinjaman.id')
            ->join($tb_kel.' as k', 'k.id', '=', 'pk.id_kel')
            ->join('desa', 'k.desa', '=', 'desa.kd_desa')
            ->where('pk.id', $id)
            ->with([
                'pinjaman_anggota' => function ($query) {
                    $query->select('id', 'id_pinkel', 'nia', 'proposal', 'verifikasi', 'alokasi');
                },
                'pinjaman_anggota.anggota' => function ($query) {
                    $query->select('id', 'namadepan', 'nik');
                },
            ])->first();

        return response()->json([
            'success' => true,
            'data' => $query,
        ], 200);
    }

    private function _saldo($tgl)
    {
        $bulan = [];
        for ($i = 0; $i <= date('m'); $i++) {
            $bulan[$i] = [
                'pendapatan' => 0,
                'beban' => 0,
            ];
        }

        $rekening = Rekening::where('lev1', '>=', '4')->with([
            'kom_saldo' => function ($query) use ($tgl) {
                $tahun = date('Y', strtotime($tgl));
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', '<=', date('m')],
                ])->orderBy('kode_akun', 'ASC')->orderBy('bulan', 'ASC');
            },
        ])->get();

        foreach ($rekening as $rek) {
            foreach ($rek->kom_saldo as $kom_saldo) {
                $debit = 0;
                if ($kom_saldo->debit) {
                    $debit = $kom_saldo->debit;
                }
                $kredit = 0;
                if ($kom_saldo->kredit) {
                    $kredit = $kom_saldo->kredit;
                }

                $saldo = $kredit - $debit;
                if ($rek->lev1 > '4') {
                    $saldo = $debit - $kredit;
                }

                if ($rek->lev1 == '4') {
                    $bulan[intval($kom_saldo->bulan)]['pendapatan'] += $saldo;
                } else {
                    $bulan[intval($kom_saldo->bulan)]['beban'] += $saldo;
                }
            }
        }

        $nama_bulan = [];
        $pendapatan = [];
        $beban = [];
        $surplus = [];
        foreach ($bulan as $key => $value) {
            $saldo_pendapatan = 0;
            $saldo_beban = 0;
            if ($key > 0) {
                $saldo_pendapatan = $value['pendapatan'] - $bulan[$key - 1]['pendapatan'];
                $saldo_beban = $value['beban'] - $bulan[$key - 1]['beban'];
            }

            $pendapatan[$key] = $saldo_pendapatan;
            $beban[$key] = $saldo_beban;
            $surplus[$key] = $saldo_pendapatan - $saldo_beban;

            if ($key == 0) {
                $nama_bulan[$key] = 'Awl';
            } else {
                $tanggal = date('Y-m-d', strtotime(date('Y').'-'.$key.'-01'));
                $nama_bulan[$key] = substr(Tanggal::namaBulan($tanggal), 0, 3);
            }
        }

        $saldo = [
            'nama_bulan' => $nama_bulan,
            'pendapatan' => $pendapatan,
            'beban' => $beban,
            'surplus' => $surplus,
        ];

        return $saldo;
    }
}
