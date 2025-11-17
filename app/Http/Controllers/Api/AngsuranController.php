<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    public function search()
    {
        $keyword = request()->get('keyword');

        $lokasi = request()->user()->lokasi;
        $pk = "pinjaman_kelompok_{$lokasi}";
        $k = "kelompok_{$lokasi}";
        $keyword = strtolower($keyword);

        $pinjamanKelompok = PinjamanKelompok::from("$pk as pk")
            ->select(
                "pk.id",
                "pk.id_kel",
                "pk.struktur_kelompok",
                "pk.tgl_cair",
                "pk.alokasi",
                "k.nama_kelompok",
                "k.kd_kelompok",
                "k.ketua"
            )
            ->join("$k as k", "k.id", "=", "pk.id_kel")
            ->where("pk.status", "A")
            ->where(function ($q) use ($keyword) {
                $q->whereRaw("LOWER(k.nama_kelompok) LIKE ?", ["%$keyword%"])
                    ->orWhereRaw("LOWER(k.kd_kelompok) LIKE ?", ["%$keyword%"])
                    ->orWhereRaw("LOWER(k.ketua) LIKE ?", ["%$keyword%"]);
            })
            ->limit(10)
            ->orderBy('k.nama_kelompok', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pinjamanKelompok
        ], 200);
    }

    public function pinjaman(PinjamanKelompok $pinjaman)
    {
        // 
    }
}
