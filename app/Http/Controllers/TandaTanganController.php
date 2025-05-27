<?php

namespace App\Http\Controllers;

use App\Models\DokumenPinjaman;
use App\Models\TandaTanganDokumen;
use Illuminate\Http\Request;
use Session;

class TandaTanganController extends Controller
{
    public function index()
    {
        $data['dokumenPinjaman'] = DokumenPinjaman::where('custom_ttd', '1')->get();
        $data['tandaTangan'] = TandaTanganDokumen::where('lokasi', Session::get('lokasi'))->pluck('tanda_tangan', 'dokumen_pinjaman_id')->toArray();

        $data['title'] = "Pengaturan Tanda Tangan";
        return view('tanda_tangan.index')->with($data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $data['tanda_tangan'] = preg_replace('/<table[^>]*>/', '<table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0">', $data['tanda_tangan'], 1);
        $data['tanda_tangan'] = preg_replace('/height:\s*[^;]+;?/', '', $data['tanda_tangan']);

        $data['tanda_tangan'] = str_replace('colgroup', 'tr', $data['tanda_tangan']);
        $data['tanda_tangan'] = preg_replace('/<col([^>]*)>/', '<td$1>&nbsp;</td>', $data['tanda_tangan']);

        $tandaTanganDokumen = TandaTanganDokumen::updateOrCreate([
            'lokasi' => Session::get('lokasi'),
            'dokumen_pinjaman_id' => $data['dokumen'],
            'jenis_laporan' => $data['jenis_laporan'],
        ], [
            'lokasi' => Session::get('lokasi'),
            'dokumen_pinjaman_id' => $data['dokumen'],
            'jenis_laporan' => $data['jenis_laporan'],
            'tanda_tangan' => json_encode($data['tanda_tangan'])
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Tanda tangan berhasil disimpan.',
            'data' => $tandaTanganDokumen,
            'tanda_tangan' => json_encode($data['tanda_tangan'])
        ]);
    }
}
