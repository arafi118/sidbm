<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUpdate;
use App\Models\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MobileActivationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function cekUpdate()
    {
        $update = AppUpdate::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $update,
        ], 200);
    }

    public function update($apk_name)
    {
        $update = AppUpdate::where('apk_name', $apk_name)->orderBy('created_at', 'desc')->first();
        $filePath = storage_path('app/public/'.$update->apk_url);

        if (! file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan',
            ], 422);
        }

        return response()->download($filePath);
    }

    public function activation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Ada form yang belum diisi',
                'form_error' => $validate->errors(),
            ], 400);
        }

        $listToken = Mobile::with('kec')->orderBy('lokasi', 'ASC')->get();
        foreach ($listToken as $token) {
            if (Hash::check($request->token, $token->aktivasi)) {
                $kec = $token->kec;
                $kecamatan = $kec->nama_lembaga_sort.' '.$kec->sebutan_kec.' '.$kec->nama_kec;

                return response()->json([
                    'success' => true,
                    'message' => 'Aktivasi SI DBM Mobile '.$kecamatan.' berhasil.',
                    'data' => [
                        'token' => $request->token,
                        'code' => $token->unique_id,
                        'lokasi' => $token->lokasi,
                    ],
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Aktivasi SI DBM Mobile gagal. Token tidak valid.',
        ], 422);
    }

    public function ambilDataLokasi()
    {
        $token = request()->header('token');
        $code = request()->header('code');

        $getToken = Mobile::where('unique_id', $code)->with('kec')->first();
        if ($getToken) {
            if (Hash::check($token, $getToken->aktivasi)) {
                $logoURI = $getToken->kec->logo;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'nama_lembaga' => $getToken->kec->nama_lembaga_sort,
                        'nama_kec' => $getToken->kec->sebutan_kec.' '.$getToken->kec->nama_kec,
                        'nama_kab' => ucwords(strtolower($getToken->kec->kabupaten->nama_kab)),
                        'logo' => $logoURI,
                    ],
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid.',
        ], 422);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Mobile $mobile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mobile $mobile)
    {
        //
    }
}
