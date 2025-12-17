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

    public function generate(Request $request, $id_kec)
    {
        $token = $request->token;

        $unique_id = uniqid();
        $hashToken = Hash::make($token);
        $expiredAt = date('Y-m-d H:i:s', strtotime('+1 day'));

        Mobile::insert([
            'lokasi' => $id_kec,
            'unique_id' => $unique_id,
            'aktivasi' => $hashToken,
            'expired_at' => $expiredAt,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Token berhasil diaktifkan',
        ]);
    }

    public function cekUpdate()
    {
        $update = AppUpdate::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $update,
        ], 200);
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

        $listToken = Mobile::where(function ($query) {
            $query->where('expired_at', '>=', now())
                ->orWhere('expired_at', null);
        })->with('kec')->orderBy('lokasi', 'ASC')->get();
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
