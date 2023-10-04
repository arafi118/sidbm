<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\TandaTanganLaporan;
use Illuminate\Http\Request;

class SopController extends Controller
{
    public function index()
    {
        $title = "Personalisasi SOP";
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('ttd')->first();

        return view('sop.index')->with(compact('title', 'kec'));
    }

    public function ttdPelaporan()
    {
        $title = "Pengaturan Tanda Tangan Pelaporan";
        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('ttd')->first();

        return view('sop.partials.ttd_pelaporan')->with(compact('title', 'kec'));
    }

    public function simpanTtdPelaporan(Request $request)
    {
        $data = $request->only([
            'field',
            'tanda_tangan'
        ]);

        $data['tanda_tangan'] = preg_replace('/<table[^>]*>/', '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">', $data['tanda_tangan'], 1);
        $data['tanda_tangan'] = preg_replace('/height:\s*[^;]+;?/', '', $data['tanda_tangan']);

        $ttd = TandaTanganLaporan::where('lokasi', auth()->user()->lokasi)->count();
        if ($ttd <= 0) {
            $insert = [
                'lokasi' => auth()->user()->lokasi
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
            $tanda_tangan = TandaTanganLaporan::where('lokasi', auth()->user()->lokasi)->update([
                $data['field'] => json_encode($data['tanda_tangan'])
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => ucwords(str_replace('_', ' ', $data['field'])) . ' Berhasil diperbarui'
        ]);
    }
}
