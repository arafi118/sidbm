<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\JenisTransaksi;
use App\Models\Kecamatan;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function jurnalUmum()
    {
        $title = 'Jurnal Umum';
        $jenis_transaksi = JenisTransaksi::all();

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();

        return view('transaksi.jurnal_umum.index')->with(compact('title', 'jenis_transaksi', 'kec'));
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

        if (Keuangan::startWith($request->sumber_dana, '1.2.02') && $request->jenis_transaksi == '2') {
            $data = $request->only([
                'tgl_transaksi',
                'jenis_transaksi',
                'sumber_dana',
                'disimpan_ke',
                'harsat',
                'nama_barang',
                'alasan',
                'unit',
                'harga_jual'
            ]);

            $validate = Validator::make($data, [
                'tgl_transaksi' => 'required',
                'jenis_transaksi' => 'required',
                'sumber_dana' => 'required',
                'disimpan_ke' => 'required',
                'harsat' => 'required',
                'nama_barang' => 'required',
                'alasan' => 'required',
                'unit' => 'required',
                'harga_jual' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
            }

            $sumber_dana = $request->sumber_dana;
            $disimpan_ke = $request->disimpan_ke;
            $nilai_buku = $request->unit * $request->harsat;
            $status = $request->alasan;

            $nama_barang = explode('#', $request->nama_barang);
            $id_inv = $nama_barang[0];
            $jumlah_barang = $nama_barang[1];

            $inv = Inventaris::where('id', $id_inv)->first();

            $tgl_beli = $inv->tgl_beli;
            $harsat = $inv->harsat;
            $umur_ekonomis = $inv->umur_ekonomis;
            $sisa_unit = $jumlah_barang - $request->unit;
            $barang = $inv->nama_barang;
            $jenis = $inv->jenis;
            $kategori = $inv->kategori;

            $trx_penghapusan = [
                'tgl_transaksi' => Tanggal::tglNasional($request->tgl_transaksi),
                'rekening_debit' => $request->disimpan_ke,
                'rekening_kredit' => $request->sumber_dana,
                'idtp' => '0',
                'id_pinj' => '0',
                'id_pinj_i' => '0',
                'keterangan_transaksi' => 'Penghapusan ' . $request->unit . ' unit ' . $barang . ' karena ' . $status,
                'relasi' => $request->relasi,
                'jumlah' => $nilai_buku,
                'urutan' => '0',
                'id_user' => auth()->user()->id,
            ];

            $update_inventaris = [
                'unit' => $sisa_unit,
                'tgl_validasi' => Tanggal::tglNasional($request->tgl_transaksi)
            ];

            $update_sts_inventaris = [
                'status' => ucwords($status),
                'tgl_validasi' => Tanggal::tglNasional($request->tgl_transaksi)
            ];

            $insert_inventaris = [
                'lokasi' => auth()->user()->lokasi,
                'nama_barang' => $barang,
                'tgl_beli' => $tgl_beli,
                'unit' => $request->unit,
                'harsat' => $harsat,
                'umur_ekonomis' => $umur_ekonomis,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'status' => ucwords($status),
                'tgl_validasi' => Tanggal::tglNasional($request->tgl_transaksi),
            ];

            $trx_penjualan = [
                'tgl_transaksi' => Tanggal::tglNasional($request->tgl_transaksi),
                'rekening_debit' => '1.1.01.01',
                'rekening_kredit' => '4.2.01.04',
                'idtp' => '0',
                'id_pinj' => '0',
                'id_pinj_i' => '0',
                'keterangan_transaksi' => 'Penjualan ' . $request->unit . ' unit ' . $barang,
                'relasi' => $request->relasi,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $request->harga_jual)),
                'urutan' => '0',
                'id_user' => auth()->user()->id,
            ];

            if ($request->unit < $jumlah_barang) {
                Transaksi::create($trx_penghapusan);
                Inventaris::where('id', $id_inv)->update($update_inventaris);
                Inventaris::create($insert_inventaris);
            } else {
                Transaksi::create($trx_penghapusan);
                Inventaris::where('id', $id_inv)->update($update_sts_inventaris);
            }

            $msg = 'Penghapusan ' . $request->unit . ' unit ' . $barang . ' karena ' . $status;
            if ($status == 'dijual') {
                Transaksi::create($trx_penjualan);
                $msg = 'Penjualan ' . $request->unit . ' unit ' . $barang;
            }
        } else {
            if (Keuangan::startWith($request->disimpan_ke, '1.2.01') || Keuangan::startWith($request->disimpan_ke, '1.2.03')) {
                $data = $request->only([
                    'tgl_transaksi',
                    'jenis_transaksi',
                    'sumber_dana',
                    'disimpan_ke',
                    'relasi',
                    'nama_barang',
                    'jumlah',
                    'harga_satuan',
                    'umur_ekonomis',
                ]);

                $validate = Validator::make($data, [
                    'tgl_transaksi' => 'required',
                    'jenis_transaksi' => 'required',
                    'sumber_dana' => 'required',
                    'disimpan_ke' => 'required',
                    'nama_barang' => 'required',
                    'jumlah' => 'required',
                    'harga_satuan' => 'required',
                    'umur_ekonomis' => 'required'
                ]);

                if ($validate->fails()) {
                    return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
                }

                $rek_simpan = Rekening::where('kode_akun', $request->disimpan_ke)->first();

                $insert = [
                    'tgl_transaksi' => Tanggal::tglNasional($request->tgl_transaksi),
                    'rekening_debit' => $request->disimpan_ke,
                    'rekening_kredit' => $request->sumber_dana,
                    'idtp' => 0,
                    'id_pinj' => 0,
                    'id_pinj_i' => 0,
                    'keterangan_transaksi' => '(' . $rek_simpan->nama_akun . ') ' . $request->nama_barang,
                    'relasi' => $request->relasi,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->harga_satuan)),
                    'urutan' => 0,
                    'id_user' => auth()->user()->id,
                ];

                $inventaris = [
                    'lokasi' => auth()->user()->lokasi,
                    'nama_barang' => $request->nama_barang,
                    'tgl_beli' => Tanggal::tglNasional($request->tgl_transaksi),
                    'unit' => $request->jumlah,
                    'harsat' => str_replace(',', '', str_replace('.00', '', $request->harga_satuan)),
                    'umur_ekonomis' => $request->umur_ekonomis,
                    'jenis' => str_pad($rek_simpan->lev3, 1, "0", STR_PAD_LEFT),
                    'kategori' => str_pad($rek_simpan->lev4, 1, "0", STR_PAD_LEFT),
                    'status' => 'Baik',
                    'tgl_validasi' => Tanggal::tglNasional($request->tgl_transaksi),
                ];

                $transaksi = Transaksi::create($insert);
                $inv = Inventaris::create($inventaris);

                $msg = 'Transaksi ' .  $rek_simpan->nama_akun . ' (' . $insert['keterangan_transaksi'] . ') berhasil disimpan';
            } else {
                $data = $request->only([
                    'tgl_transaksi',
                    'jenis_transaksi',
                    'sumber_dana',
                    'disimpan_ke',
                    'relasi',
                    'keterangan',
                    'nominal'
                ]);

                $validate = Validator::make($data, [
                    'tgl_transaksi' => 'required',
                    'jenis_transaksi' => 'required',
                    'sumber_dana' => 'required',
                    'disimpan_ke' => 'required',
                    'nominal' => 'required'
                ]);

                if ($validate->fails()) {
                    return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
                }

                $insert = [
                    'tgl_transaksi' => Tanggal::tglNasional($request->tgl_transaksi),
                    'rekening_debit' => $request->disimpan_ke,
                    'rekening_kredit' => $request->sumber_dana,
                    'idtp' => 0,
                    'id_pinj' => 0,
                    'id_pinj_i' => 0,
                    'keterangan_transaksi' => $request->keterangan,
                    'relasi' => $request->relasi,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->nominal)),
                    'urutan' => 0,
                    'id_user' => auth()->user()->id,
                ];

                // $transaksi = Transaksi::create($insert);
                $msg = 'Transaksi ' . $insert['keterangan_transaksi'] . ' berhasil disimpan';
            }
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }

    public function rekening($id)
    {
        $jenis_transaksi = JenisTransaksi::where('id', $id)->firstOrFail();
        $label1 = 'Pilih Sumber Dana';

        if ($id == 1) {
            $rek1 = Rekening::where(function ($query) {
                $query->where('lev1', '2')->orwhere('lev1', '3')->orwhere('lev1', '4');
            })->where([
                ['kode_akun', '!=', '2.1.04.01'],
                ['kode_akun', '!=', '2.1.04.02'],
                ['kode_akun', '!=', '2.1.04.03'],
                ['kode_akun', '!=', '2.1.02.01'],
                ['kode_akun', '!=', '2.1.03.01'],
                ['kode_akun', 'NOT LIKE', '4.1.01%']
            ])->orderBy('kode_akun', 'ASC')->get();

            $rek2 = Rekening::where('lev1', '1')->orderBy('kode_akun', 'ASC')->get();

            $label2 = 'Pilih Brangkas';
        } elseif ($id == 2) {
            $rek1 = Rekening::where(function ($query) {
                $query->where('lev1', '1')->orwhere('lev1', '2');
            })->where([
                ['kode_akun', 'NOT LIKE', '2.1.04%']
            ])->orderBy('kode_akun', 'ASC')->get();

            $rek2 = Rekening::where('lev1', '2')->orwhere('lev1', '3')->orwhere('lev1', '5')->orderBy('kode_akun', 'ASC')->get();

            $label2 = 'Pilih Biaya';
        } elseif ($id == 3) {
            $rek1 = Rekening::where('lev1', '1')->orwhere('kode_akun', 'like', '5.1.01%')->orwhere('kode_akun', 'like', '5.1.02%')
                ->orwhere('kode_akun', 'like', '5.1.06%')->orwhere('kode_akun', 'like', '5.4.01%')->orderBy('kode_akun', 'ASC')->get();

            $rek2 = Rekening::where('lev1', '1')->orwhere('kode_akun', 'like', '2.1.03%')->orwhere('lev1', 'like', '2.1.02%')->orderBy('kode_akun', 'ASC')->get();

            $label2 = 'Pilih Brangkas Tujuan';
        }

        return view('transaksi.jurnal_umum.partials.rekening')->with(compact('rek1', 'rek2', 'label1', 'label2'));
    }

    public function form()
    {
        $tgl_transaksi = Tanggal::tglNasional(request()->get('tgl_transaksi'));
        $jenis_transaksi = request()->get('jenis_transaksi');
        $sumber_dana = request()->get('sumber_dana');
        $disimpan_ke = request()->get('disimpan_ke');

        if (Keuangan::startWith($sumber_dana, '1.2.02') && $jenis_transaksi == 2) {
            $inventaris = Inventaris::where([
                ['status', 'Baik'],
                ['jenis', '1'],
                ['kategori', '4']
            ])->get();
            return view('transaksi.jurnal_umum.partials.form_hapus_inventaris')->with(compact('inventaris', 'tgl_transaksi'));
        } else {
            if (Keuangan::startWith($disimpan_ke, '1.2.01') || Keuangan::startWith($disimpan_ke, '1.2.03')) {
                $kuitansi = false;
                $relasi = false;
                if (Keuangan::startWith($disimpan_ke, '1.1.01') && !Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bkm";
                    $files = "BKM";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (!Keuangan::startWith($disimpan_ke, '1.1.01') && Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bkk";
                    $files = "BKK";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.01') && Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.02') && !(Keuangan::startWith($sumber_dana, '1.1.01') || Keuangan::startWith($sumber_dana, '1.1.02'))) {
                    $file = "c_bkm";
                    $files = "BKM";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.02') && Keuangan::startWith($sumber_dana, '1.1.02')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (Keuangan::startWith($disimpan_ke, '5.') && !(Keuangan::startWith($sumber_dana, '1.1.01') || Keuangan::startWith($sumber_dana, '1.1.02'))) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (!(Keuangan::startWith($disimpan_ke, '1.1.01') || Keuangan::startWith($disimpan_ke, '1.1.02')) && Keuangan::startWith($sumber_dana, '1.1.02')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (!(Keuangan::startWith($disimpan_ke, '1.1.01') || Keuangan::startWith($disimpan_ke, '1.1.02')) && Keuangan::startWith($sumber_dana, '4.')) {
                    $file = "c_bm";
                    $files = "BM";
                }

                return view('transaksi.jurnal_umum.partials.form_inventaris')->with(compact('relasi'));
            } else {
                $rek_sumber = Rekening::where('kode_akun', $sumber_dana)->first();
                $rek_simpan = Rekening::where('kode_akun', $disimpan_ke)->first();

                $keterangan_transaksi = '';
                if ($jenis_transaksi == 1) {
                    if (!empty($disimpan_ke)) {
                        $keterangan_transaksi = "Dari " . $rek_sumber->nama_akun . " ke " . $rek_simpan->nama_akun;
                    }
                } else if ($jenis_transaksi == 2) {
                    if (!empty($disimpan_ke)) {
                        $keterangan_transaksi = $rek_simpan->nama_akun;
                        $kd = substr($sumber_dana, 0, 6);
                        if ($kd == '1.1.01') {
                            $keterangan_transaksi = "Bayar " . $rek_simpan->nama_akun;
                        }
                        if ($kd == '1.1.02') {
                            $keterangan_transaksi = "Transfer " . $rek_simpan->nama_akun;
                        }
                    }
                } else if ($jenis_transaksi == 3) {
                    if (!empty($disimpan_ke)) {
                        $keterangan_transaksi = "Pemindahan Saldo " . $rek_sumber->nama_akun . " ke " . $rek_simpan->nama_akun;
                    }
                }

                $kuitansi = false;
                $relasi = false;
                if (Keuangan::startWith($disimpan_ke, '1.1.01') && !Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bkm";
                    $files = "BKM";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (!Keuangan::startWith($disimpan_ke, '1.1.01') && Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bkk";
                    $files = "BKK";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.01') && Keuangan::startWith($sumber_dana, '1.1.01')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.02') && !(Keuangan::startWith($sumber_dana, '1.1.01') || Keuangan::startWith($sumber_dana, '1.1.02'))) {
                    $file = "c_bkm";
                    $files = "BKM";
                    $kuitansi = true;
                    $relasi = true;
                } elseif (Keuangan::startWith($disimpan_ke, '1.1.02') && Keuangan::startWith($sumber_dana, '1.1.02')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (Keuangan::startWith($disimpan_ke, '5.') && !(Keuangan::startWith($sumber_dana, '1.1.01') || Keuangan::startWith($sumber_dana, '1.1.02'))) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (!(Keuangan::startWith($disimpan_ke, '1.1.01') || Keuangan::startWith($disimpan_ke, '1.1.02')) && Keuangan::startWith($sumber_dana, '1.1.02')) {
                    $file = "c_bm";
                    $files = "BM";
                } elseif (!(Keuangan::startWith($disimpan_ke, '1.1.01') || Keuangan::startWith($disimpan_ke, '1.1.02')) && Keuangan::startWith($sumber_dana, '4.')) {
                    $file = "c_bm";
                    $files = "BM";
                }

                return view('transaksi.jurnal_umum.partials.form_nominal')->with(compact('relasi', 'keterangan_transaksi'));
            }
        }
    }
}
