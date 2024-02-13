<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\SebutanDesa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Session;
use Yajra\DataTables\Facades\DataTables;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
            $data = Desa::where('kd_kec', $kec->kd_kec)->get();
            return DataTables::of($data)
                ->make(true);
        }

        $title = 'Data Desa';
        $sebutan = SebutanDesa::all();
        return view('desa.index')->with(compact('sebutan', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Desa $desa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Desa $desa)
    {
        $sebutan = SebutanDesa::all();
        return view('desa.edit')->with(compact('desa', 'sebutan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Desa $desa)
    {
        $data = $request->only([
            "nama_desa",
            "telp_desa",
            "alamat_desa",
            "kades",
            "no_kades",
            "nip",
            "sekdes",
            "no_sekdes",
            "deskripsi_desa",
            "ked",
            "sebutan",
            "jadwal_angsuran_desa"
        ]);

        $validate = Validator::make($data, [
            "nama_desa" => 'required',
            "telp_desa" => 'required',
            "alamat_desa" => 'required',
            "kades" => 'required',
            "no_kades" => 'required',
            "nip" => 'required',
            "sekdes" => 'required',
            "no_sekdes" => 'required',
            "deskripsi_desa" => 'required',
            "ked" => 'required',
            "sebutan" => 'required',
            "jadwal_angsuran_desa" => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        Desa::where('kd_desa', $desa->kd_desa)->update($data);
        return response()->json([
            'msg' => 'Desa ' . $desa->nama_desa . ' berhasil diperbarui'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desa $desa)
    {
        //
    }
}
