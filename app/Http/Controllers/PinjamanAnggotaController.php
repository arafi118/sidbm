<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\DataPemanfaat;
use App\Models\Kelompok;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanKelompok;
use App\Models\StatusPinjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PinjamanAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id_pinkel)
    {
        $pinkel = PinjamanKelompok::where('id', $id_pinkel);

        if ($pinkel->count() >= '0') {
            $nia = request()->get('id');

            $anggota = Anggota::where('id', $nia)->where('nik', request()->get('value'))->first();

            $pinjaman_anggota = PinjamanAnggota::where('nia', $nia)->where(function (Builder $query) {
                $query->where('status', 'P')->orwhere('status', 'V')->orwhere('status', 'W');
            });
            $jumlah_pinjaman_anggota = $pinjaman_anggota->count();
            $pinjaman_anggota = $pinjaman_anggota->with('sts')->orderby('tgl_proposal', 'desc')->first();

            $pinjaman_anggota_a = PinjamanAnggota::where('nia', $nia)->where('status', 'A');
            $jumlah_pinjaman_anggota_a = $pinjaman_anggota_a->count();
            $pinjaman_anggota_a = $pinjaman_anggota_a->with('sts')->orderby('tgl_cair', 'desc')->first();

            $data_pemanfaat = DataPemanfaat::where([
                ['nik', request()->get('value')],
                ['lokasi', '!=', auth()->user()->lokasi]
            ])->where(function (Builder $query) {
                $query->where('status', 'P')->orwhere('status', 'V')->orwhere('status', 'W');
            });
            $jumlah_data_pemanfaat = $data_pemanfaat->count();
            $data_pemanfaat = $data_pemanfaat->with('sts', 'kec')->first();

            $data_pemanfaat_a = DataPemanfaat::where([
                ['nik', request()->get('value')],
                ['lokasi', '!=', auth()->user()->lokasi]
            ])->where('status', 'A');
            $jumlah_data_pemanfaat_a = $data_pemanfaat_a->count();
            $data_pemanfaat_a = $data_pemanfaat_a->with('sts', 'kec')->first();

            $enable_alokasi = true;
            if ($jumlah_pinjaman_anggota > 0 || $jumlah_data_pemanfaat > 0) $enable_alokasi = false;

            $view = view('pinjaman.anggota.register')->with(compact('anggota', 'pinjaman_anggota', 'jumlah_pinjaman_anggota', 'pinjaman_anggota_a', 'jumlah_pinjaman_anggota_a', 'data_pemanfaat', 'jumlah_data_pemanfaat', 'data_pemanfaat_a', 'jumlah_data_pemanfaat_a'))->render();
            return [
                'nia' => $nia,
                'enable_alokasi' => $enable_alokasi,
                'html' => $view
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'id_pinkel',
            'nia_pemanfaat',
            'alokasi_pengajuan'
        ]);

        $validate = Validator::make($data, [
            'id_pinkel' => 'required',
            'nia_pemanfaat' => 'required',
            'alokasi_pengajuan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $pinkel = PinjamanKelompok::where('id', $request->id_pinkel)->first();
        $anggota = Anggota::where('id', $request->nia_pemanfaat)->first();

        $insert = [
            'jenis_pinjaman' => 'K',
            'id_kel' => $pinkel->id_kel,
            'id_pinkel' => $pinkel->id,
            'jenis_pp' => $pinkel->jenis_pp,
            'nia' => $request->nia_pemanfaat,
            'tgl_proposal' => $pinkel->tgl_proposal,
            'tgl_verifikasi' => $pinkel->tgl_proposal,
            'tgl_dana' => $pinkel->tgl_proposal,
            'tgl_tunggu' => $pinkel->tgl_proposal,
            'tgl_cair' => $pinkel->tgl_proposal,
            'tgl_lunas' => $pinkel->tgl_proposal,
            'proposal' => str_replace(',', '', str_replace('.00', '', $request->alokasi_pengajuan)),
            'verifikasi' => str_replace(',', '', str_replace('.00', '', $request->alokasi_pengajuan)),
            'alokasi' => str_replace(',', '', str_replace('.00', '', $request->alokasi_pengajuan)),
            'kom_pokok' => '0',
            'kom_jasa' => '0',
            'spk_no' => $pinkel->spk_no,
            'sumber' => $pinkel->sumber,
            'pros_jasa' => $pinkel->pros_jasa,
            'jenis_jasa' => $pinkel->jenis_jasa,
            'jangka' => $pinkel->jangka,
            'sistem_angsuran' => $pinkel->sistem_angsuran,
            'sa_jasa' => $pinkel->sa_jasa,
            'status' => $pinkel->status,
            'catatan_verifikasi' => $pinkel->catatan_verifikasi,
            'lu' => date('Y-m-d H:i:s'),
            'user_id' => auth()->user()->id,
        ];

        $pinjaman_anggota = PinjamanAnggota::create($insert);
        $data_pemanfaat = DataPemanfaat::create([
            'lokasi' => auth()->user()->lokasi,
            'nik' => $anggota->nik,
            'id_pinkel' => $insert['id_pinkel'],
            'idpa' => $pinjaman_anggota->id,
            'status' => $insert['status']
        ]);

        return response()->json([
            'msg' => 'Pemanfaat atas nama ' . $anggota->namadepan . ' berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PinjamanAnggota $pinjaman_anggotum)
    {
        //
    }

    public function cariPemanfaat()
    {
        $param = request()->get('query');
        if (strlen($param) >= '0') {
            $pinkel = PinjamanKelompok::where('id', request()->get('loan_id'))->first();
            $kel = Kelompok::where('id', $pinkel->id_kel)->first();

            $anggota = Anggota::where('desa', $kel->desa)->where(function (Builder $query) {
                $query->where('namadepan', 'like', '%' . request()->get('query') . '%')
                    ->orwhere('nik', 'like', '%' . request()->get('query') . '%');
            })->orderBy('id', 'DESC')->get();

            return response()->json($anggota);
        }

        return response()->json($param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PinjamanAnggota $pinjaman_anggotum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PinjamanAnggota $pinjaman_anggotum)
    {
        if ($request->status == 'P') {
            $jumlah = 'proposal';
        } elseif ($request->status == 'V') {
            $jumlah = 'verifikasi';
        } elseif ($request->status == 'W') {
            $jumlah = 'alokasi';
        } else {
            $jumlah = 'alokasi';
        }

        $data = $request->only([
            'idpa',
            $jumlah,
            'status',
        ]);

        $nominal =  ($data[$jumlah] == '') ? 0 : str_replace(',', '', str_replace('.00', '', $data[$jumlah]));
        PinjamanAnggota::where('id', $pinjaman_anggotum->id)->update([
            $jumlah => $nominal,
        ]);

        $jumlah = PinjamanAnggota::where('id_pinkel', $pinjaman_anggotum->id_pinkel)->sum($jumlah);
        return response()->json([
            'jumlah' => number_format($jumlah, 2)
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PinjamanAnggota $pinjaman_anggotum)
    {
        //
    }

    public function hapus($id)
    {
        $pinjaman_anggota = PinjamanAnggota::where('id', $id)->with('anggota')->first();
        if ($pinjaman_anggota->status == 'P') {
            PinjamanAnggota::where('id', $id)->delete();
            DataPemanfaat::where([
                ['idpa', $id],
                ['lokasi', auth()->user()->lokasi]
            ])->delete();

            return response()->json([
                'hapus' => true,
                'msg' => 'Pemanfaat atas nama ' . $pinjaman_anggota->anggota->namadepan . ' berhasil dihapus'
            ]);
        }

        return response()->json([
            'hapus' => false,
            'msg' => 'Pemanfaat atas nama ' . $pinjaman_anggota->anggota->namadepan . ' gagal dihapus'
        ]);
    }
}
