<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mobile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        $data = $request->only([
            'username',
            'password'
        ]);

        $validate = Validator::make($data, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()
            ], 400);
        }

        $token = request()->header('token');
        $code = request()->header('code');

        $getToken = Mobile::where('unique_id', $code)->with('kec')->first();
        if ($getToken) {
            if (Hash::check($token, $getToken->aktivasi)) {
                $kecamatan = $getToken->kec;
                $lokasi = $kecamatan->id;

                $user = User::where([['uname', $data['username']], ['lokasi', $lokasi]])->first();
                // dd($user, [['uname', $data['username']], ['lokasi', $lokasi]]);
                if ($user) {
                    if ($data['password'] === $user->pass) {
                        if (Auth::loginUsingId($user->id)) {
                            $token = $user->createToken($token)->plainTextToken;

                            unset($user['pass']);
                            return response()->json([
                                'success' => true,
                                'message' => 'Login successful',
                                'data' => [
                                    'user' => [
                                        'id' => $user->id,
                                        'nama' => $user->namadepan . ' ' . $user->namabelakang,
                                        'jabatan' => $user->j->nama_jabatan,
                                        'profil' => $user->foto
                                    ],
                                    'token' => $token,
                                    'token_type' => 'Bearer'
                                ]
                            ]);
                        }
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Username atau password salah.'
                ], 401);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid.'
        ], 400);
    }
}
