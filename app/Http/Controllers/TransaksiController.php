<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\JenisTransaksi;
use App\Models\Kecamatan;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\Rekening;
use App\Models\RencanaAngsuran;
use App\Models\Transaksi;
use App\Models\User;
use App\Utils\Inventaris as UtilsInventaris;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use DB;
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

    public function jurnalAngsuran()
    {
        $title = 'Jurnal Angsuran';

        if (request()->get('pinkel')) {
            $pinkel = PinjamanKelompok::where('id', request()->get('pinkel'))->with('kelompok');
            $pinkel = $pinkel->first();
        } else {
            $pinkel = '0';
        }

        return view('transaksi.jurnal_angsuran.index')->with(compact('title', 'pinkel'));
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

        if (Keuangan::startWith($request->sumber_dana, '1.2.02') && Keuangan::startWith($request->disimpan_ke, '5.3.02.01') && $request->jenis_transaksi == '2') {
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
                'tgl_transaksi' => (string) Tanggal::tglNasional($request->tgl_transaksi),
                'rekening_debit' => (string) $request->disimpan_ke,
                'rekening_kredit' => (string) $request->sumber_dana,
                'idtp' => '0',
                'id_pinj' => '0',
                'id_pinj_i' => '0',
                'keterangan_transaksi' => (string) 'Penghapusan ' . $request->unit . ' unit ' . $barang . ' karena ' . $status,
                'relasi' => (string) $request->relasi,
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
                'tgl_transaksi' => (string) Tanggal::tglNasional($request->tgl_transaksi),
                'rekening_debit' => '1.1.01.01',
                'rekening_kredit' => '4.2.01.04',
                'idtp' => '0',
                'id_pinj' => '0',
                'id_pinj_i' => '0',
                'keterangan_transaksi' => (string) 'Penjualan ' . $request->unit . ' unit ' . $barang,
                'relasi' => (string) $request->relasi,
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
                    'tgl_transaksi' => (string) Tanggal::tglNasional($request->tgl_transaksi),
                    'rekening_debit' => (string) $request->disimpan_ke,
                    'rekening_kredit' => (string) $request->sumber_dana,
                    'idtp' => 0,
                    'id_pinj' => 0,
                    'id_pinj_i' => 0,
                    'keterangan_transaksi' => (string) '(' . $rek_simpan->nama_akun . ') ' . $request->nama_barang,
                    'relasi' => (string) $request->relasi,
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

                $relasi = '';
                if ($request->relasi) $relasi = $request->relasi;
                $insert = [
                    'tgl_transaksi' => (string) Tanggal::tglNasional($request->tgl_transaksi),
                    'rekening_debit' => (string) $request->disimpan_ke,
                    'rekening_kredit' => (string) $request->sumber_dana,
                    'idtp' => 0,
                    'id_pinj' => 0,
                    'id_pinj_i' => 0,
                    'keterangan_transaksi' => (string) $request->keterangan,
                    'relasi' => (string) $relasi,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->nominal)),
                    'urutan' => 0,
                    'id_user' => auth()->user()->id,
                ];

                $transaksi = Transaksi::create($insert);
                $msg = 'Transaksi ' . $insert['keterangan_transaksi'] . ' berhasil disimpan';
            }
        }

        return response()->json([
            'msg' => $msg
        ]);
    }

    public function angsuran(Request $request)
    {
        $last_idtp = Transaksi::where('idtp', '!=', '0')->max('idtp');
        $data = $request->only([
            'id',
            'tgl_transaksi',
            'pokok',
            'jasa',
            'denda'
        ]);

        $validate = Validator::make($data, [
            'tgl_transaksi' => 'required',
            'pokok' => 'required',
            'jasa' => 'required',
            'denda' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $request->pokok = str_replace(',', '', str_replace('.00', '', $request->pokok));
        $request->jasa = str_replace(',', '', str_replace('.00', '', $request->jasa));
        $request->denda = str_replace(',', '', str_replace('.00', '', $request->denda));

        if ($request->pokok <= 0 && $request->jasa <= 0 && $request->denda <= 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Total Bayar tidak boleh nol'
            ]);
        }

        $tgl_transaksi = Tanggal::tglNasional($request->tgl_transaksi);

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->first();
        $pinkel = PinjamanKelompok::where('id', $request->id)->with('kelompok')->first();

        $real = RealAngsuran::where([
            ['loan_id', $pinkel->id],
            ['tgl_transaksi', '<=', $tgl_transaksi]
        ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');

        $ra = RencanaAngsuran::where([
            ['loan_id', $pinkel->id],
            ['jatuh_tempo', '<=', $tgl_transaksi]
        ])->orderBy('jatuh_tempo', 'DESC');

        if ($real->count() > 0) {
            $real = $real->first();
        } else {
            $real->sum_pokok = 0;
            $real->sum_jasa = 0;
        }

        if ($ra->count() > 0) {
            $ra = $ra->first();
        } else {
            $ra->target_pokok = 0;
            $ra->target_jasa = 0;
        }

        $tunggakan_jasa    = $ra->target_jasa - $real->sum_jasa;
        if ($tunggakan_jasa < '0') $tunggakan_jasa = '0';

        if (strtotime($tgl_transaksi) < strtotime($pinkel->tgl_cair)) {
            return response()->json([
                'success' => false,
                'msg' => 'Tanggal transaksi tidak boleh sebelum Tanggal Cair'
            ]);
        }

        $kas_umum = '1.1.01.01';
        if ($pinkel->jenis_pp == '1') {
            $poko_kredit = '1.1.03.01';
            $jasa_kredit = '4.1.01.01';
            $dend_kredit = '4.1.01.04';
        } elseif ($pinkel->jenis_pp == '2') {
            $poko_kredit = '1.1.03.02';
            $jasa_kredit = '4.1.01.02';
            $dend_kredit = '4.1.01.05';
        } else {
            $poko_kredit = '1.1.03.03';
            $jasa_kredit = '4.1.01.03';
            $dend_kredit = '4.1.01.06';
        }

        // if (strtotime($tgl_transaksi) < strtotime($kec->tgl_registrasi)) {
        //     return response()->json([
        //         'success' => false,
        //         'msg' => 'Tanggal transaksi tidak boleh sebelum Tanggal Registrasi Aplikasi'
        //     ]);
        // }

        $idtp = $last_idtp + 1;
        if ($request->pokok > 0) {
            $trx_pokok = [
                'tgl_transaksi' => (string) $tgl_transaksi,
                'rekening_debit' => (string) $kas_umum,
                'rekening_kredit' => (string) $poko_kredit,
                'idtp' => $idtp,
                'id_pinj' => $pinkel->id,
                'id_pinj_i' => '0',
                'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] (P)',
                'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $request->pokok)),
                'urutan' => '0',
                'id_user' => auth()->user()->id
            ];

            $pokok = Transaksi::create($trx_pokok);
        }

        if ($request->jasa > 0) {
            if ($request->jasa < $tunggakan_jasa) {
                if ($pinkel->jenis_pp == '1') {
                    $jasa_kredit = '1.1.03.04';
                } elseif ($pinkel->jenis_pp == '2') {
                    $jasa_kredit = '1.1.03.05';
                } else {
                    $jasa_kredit = '1.1.03.06';
                }

                $trx_jasa = [
                    'tgl_transaksi' => (string) $tgl_transaksi,
                    'rekening_debit' => (string) $kas_umum,
                    'rekening_kredit' => (string) $jasa_kredit,
                    'idtp' => $idtp,
                    'id_pinj' => $pinkel->id,
                    'id_pinj_i' => '0',
                    'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] (J)',
                    'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->jasa)),
                    'urutan' => '0',
                    'id_user' => auth()->user()->id
                ];

                $jasa = Transaksi::create($trx_jasa);
            } else {
                $angs_jasa = ($request->jasa - $tunggakan_jasa > 0) ? ($request->jasa - $tunggakan_jasa) : $request->jasa;
                $trx_jasa = [
                    'tgl_transaksi' => (string) $tgl_transaksi,
                    'rekening_debit' => (string) $kas_umum,
                    'rekening_kredit' => (string) $jasa_kredit,
                    'idtp' => $idtp,
                    'id_pinj' => $pinkel->id,
                    'id_pinj_i' => '0',
                    'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' - [' . $pinkel->kelompok->d->nama_desa . '] (J)',
                    'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $angs_jasa)),
                    'urutan' => '0',
                    'id_user' => auth()->user()->id
                ];
                $jasa = Transaksi::create($trx_jasa);

                if ($request->jasa - $angs_jasa > 0) {
                    $piutang_kredit = $jasa_kredit;
                    if ($pinkel->jenis_pp == '1') {
                        $piutang_kredit = '1.1.03.04';
                    } elseif ($pinkel->jenis_pp == '2') {
                        $piutang_kredit = '1.1.03.05';
                    } else {
                        $piutang_kredit = '1.1.03.06';
                    }

                    $piutang_jasa = $request->jasa - $angs_jasa;
                    $trx_jasa = [
                        'tgl_transaksi' => (string) $tgl_transaksi,
                        'rekening_debit' => (string) $kas_umum,
                        'rekening_kredit' => (string) $piutang_kredit,
                        'idtp' => $idtp,
                        'id_pinj' => $pinkel->id,
                        'id_pinj_i' => '0',
                        'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' - [' . $pinkel->kelompok->d->nama_desa . '] (J)',
                        'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                        'jumlah' => str_replace(',', '', str_replace('.00', '', $piutang_jasa)),
                        'urutan' => '0',
                        'id_user' => auth()->user()->id
                    ];

                    $piutang = Transaksi::create($trx_jasa);
                }
            }
        }

        if ($request->denda > 0) {
            $trx_denda = [
                'tgl_transaksi' => (string) $tgl_transaksi,
                'rekening_debit' => (string) $kas_umum,
                'rekening_kredit' => (string) $dend_kredit,
                'idtp' => $idtp,
                'id_pinj' => $pinkel->id,
                'id_pinj_i' => '0',
                'keterangan_transaksi' => (string) 'Denda. ' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . ']',
                'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                'jumlah' => str_replace(',', '', str_replace('.00', '', $request->denda)),
                'urutan' => '0',
                'id_user' => auth()->user()->id
            ];

            $denda = Transaksi::create($trx_denda);
        }

        $this->regenerateReal($pinkel->id);

        return response()->json([
            'success' => true,
            'msg' => 'Angsuran kelompok ' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] berhasil diposting',
            'id_pinkel' => $pinkel->id,
            'idtp' => $idtp,
            'tgl_transaksi' => $tgl_transaksi
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
        $keuangan = new Keuangan;
        $tgl_transaksi = Tanggal::tglNasional(request()->get('tgl_transaksi'));
        $jenis_transaksi = request()->get('jenis_transaksi');
        $sumber_dana = request()->get('sumber_dana');
        $disimpan_ke = request()->get('disimpan_ke');

        if (Keuangan::startWith($sumber_dana, '1.2.02') && Keuangan::startWith($disimpan_ke, '5.3.02.01') && $jenis_transaksi == 2) {
            if ($sumber_dana == '1.2.02.01') {
                $jenis = '1';
                $kategori = '2';
            } elseif ($sumber_dana == '1.2.02.02') {
                $jenis = '1';
                $kategori = '3';
            } else {
                $jenis = '1';
                $kategori = '4';
            }

            $inventaris = Inventaris::where([
                ['jenis', $jenis],
                ['kategori', $kategori]
            ])->where(function ($query) {
                $query->where('status', 'Baik')->orwhere('status', 'Rusak');
            })->get();
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

                $susut = 0;
                if (Keuangan::startWith($disimpan_ke, '5.1.07.10')) {
                    $tanggal = date('Y-m-t', strtotime($tgl_transaksi));
                    if ($sumber_dana == '1.2.02.01') {
                        $kategori = '2';
                    } elseif ($sumber_dana == '1.2.02.02') {
                        $kategori = '3';
                    } else {
                        $kategori = '4';
                    }

                    $penyusutan = UtilsInventaris::penyusutan($tanggal, $kategori);
                    $saldo = UtilsInventaris::saldoSusut($tanggal, $sumber_dana);

                    $susut = $penyusutan - $saldo;
                    if ($susut < 0) $susut *= -1;
                    $keterangan_transaksi .= ' (' . Tanggal::namaBulan($tgl_transaksi) . ')';
                }

                return view('transaksi.jurnal_umum.partials.form_nominal')->with(compact('relasi', 'keterangan_transaksi', 'susut'));
            }
        }
    }

    public function formAngsuran($id_pinkel)
    {
        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->with('kelompok')->firstOrFail();
        $real = RealAngsuran::where([
            ['loan_id', $id_pinkel],
            ['tgl_transaksi', '<=', date('Y-m-d')]
        ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');
        $ra = RencanaAngsuran::where([
            ['loan_id', $id_pinkel],
            ['jatuh_tempo', '<=', date('Y-m-d')],
            ['angsuran_ke', '!=', '0']
        ])->orderBy('jatuh_tempo', 'DESC');

        $alokasi_jasa = $pinkel->alokasi * ($pinkel->pros_jasa / 100);

        if ($real->count() > 0) {
            $real = $real->first();
        } else {
            $real->sum_pokok = 0;
            $real->sum_jasa = 0;
        }

        if ($ra->count() > 0) {
            $ra = $ra->first();
        } else {
            $ra->target_pokok = 0;
            $ra->target_jasa = 0;
        }

        $saldo_pokok = ($ra->target_pokok - $real->sum_pokok > 0) ? $ra->target_pokok - $real->sum_pokok : 0;
        $saldo_jasa = ($ra->target_jasa - $real->sum_jasa > 0) ? $ra->target_jasa - $real->sum_jasa : 0;

        $sisa_pokok = $pinkel->alokasi - $real->sum_pokok;
        $sisa_jasa = $alokasi_jasa - $real->sum_jasa;

        $sum_pokok = $real->sum_pokok;
        $sum_jasa = $real->sum_jasa;

        return response()->json([
            'saldo_pokok' => $saldo_pokok,
            'saldo_jasa' => $saldo_jasa,
            'sisa_pokok' => $sisa_pokok,
            'sisa_jasa' => $sisa_jasa,
            'sum_pokok' => $sum_pokok,
            'sum_jasa' => $sum_jasa,
            'alokasi_pokok' => $pinkel->alokasi,
            'alokasi_jasa' => $alokasi_jasa,
            'pinkel' => $pinkel
        ]);
    }

    public function detailTransaksi(Request $request)
    {
        $keuangan = new Keuangan;

        $data['kode_akun'] = $request->kode_akun;
        $data['tahun'] = $request->tahun;
        $data['bulan'] = $request->bulan;
        $data['hari'] = $request->hari;

        $thn = $data['tahun'];
        $bln = $data['bulan'];
        $hari = $data['hari'];

        $tgl = $thn . '-' . $bln . '-' . $hari;
        if (strlen($hari) > 0 && strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln . '-' . $hari;
            $data['sub_judul'] = 'Tanggal ' . $hari . ' ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $awal_bulan = $tgl;
        } elseif (strlen($bln) > 0) {
            $tgl = $thn . '-' . $bln;
            $data['sub_judul'] = 'Bulan ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $bulan_lalu = date('m', strtotime('-1 month', strtotime($tgl . '-01')));
            $awal_bulan = $thn . '-' . $bulan_lalu . '-' . date('t', strtotime($thn . '-' . $bulan_lalu));
            if ($bln == 1) {
                $awal_bulan = $thn . '00-00';
            }
        } else {
            $tgl = $thn;
            $data['sub_judul'] = 'Tahun ' . Tanggal::tahun($tgl);
            $awal_bulan = ($thn - 1) . '12-31';
        }

        $data['rek'] = Rekening::where('kode_akun', $data['kode_akun'])->first();
        $data['transaksi'] = Transaksi::where('tgl_transaksi', 'LIKE', '%' . $tgl . '%')->where(function ($query) use ($data) {
            $query->where('rekening_debit', $data['kode_akun'])->orwhere('rekening_kredit', $data['kode_akun']);
        })->with('user')->orderBy('idt', 'ASC')->get();

        $data['keuangan'] = $keuangan;
        $data['saldo'] = $keuangan->saldoAwal($tgl, $data['kode_akun']);
        $data['d_bulan_lalu'] = $keuangan->saldoD($awal_bulan, $data['kode_akun']);
        $data['k_bulan_lalu'] = $keuangan->saldoK($awal_bulan, $data['kode_akun']);

        return [
            'label' => '<i class="fas fa-book"></i> ' . $data['rek']->kode_akun . ' - ' . $data['rek']->nama_akun . ' ' . $data['sub_judul'],
            'view' => view('transaksi.jurnal_umum.partials.jurnal', $data)->render()
        ];
    }

    public function data($idt)
    {
        $trx = Transaksi::where('idt', $idt)->first();
        return response()->json([
            'idt' => $trx->idt,
            'idtp' => $trx->idtp,
            'id_pinj' => $trx->id_pinj,
            'jumlah' => number_format($trx->jumlah)
        ]);
    }

    public function reversal(Request $request)
    {
        $last_idtp = Transaksi::where('idtp', '!=', '0')->max('idtp');
        $idt = $request->rev_idt;
        $idtp = $request->rev_idtp;
        $id_pinj = $request->rev_id_pinj;

        $angsuran = false;
        if ($idtp != '0') {
            $transaksi = Transaksi::where('idtp', $idtp)->orderBy('idtp', 'ASC')->get();
            foreach ($transaksi as $trx) {
                $reversal = Transaksi::create([
                    'tgl_transaksi' => (string) date('Y-m-d'),
                    'rekening_debit' => (string) $trx->rekening_debit,
                    'rekening_kredit' => (string) $trx->rekening_kredit,
                    'idtp' => $last_idtp + 1,
                    'id_pinj' => $trx->id_pinj,
                    'id_pinj_i' => $trx->id_pinj_i,
                    'keterangan_transaksi' => (string) 'KOREKSI idt (' . $idt . ') : ' . $trx->keterangan_transaksi,
                    'relasi' => (string) $trx->relasi,
                    'jumlah' => ($trx->jumlah * -1),
                    'urutan' => $trx->urutan,
                    'id_user' => auth()->user()->id
                ]);
            }

            $angsuran = true;
        } else {
            $trx = Transaksi::where('idt', $idt)->first();

            $reversal = Transaksi::create([
                'tgl_transaksi' => (string) date('Y-m-d'),
                'rekening_debit' => (string) $trx->rekening_debit,
                'rekening_kredit' => (string) $trx->rekening_kredit,
                'idtp' => $trx->idtp,
                'id_pinj' => $trx->id_pinj,
                'id_pinj_i' => $trx->id_pinj_i,
                'keterangan_transaksi' => (string) 'KOREKSI idt (' . $idt . ') : ' . $trx->keterangan_transaksi,
                'relasi' => (string) $trx->relasi,
                'jumlah' => ($trx->jumlah * -1),
                'urutan' => $trx->urutan,
                'id_user' => auth()->user()->id
            ]);
        }

        if ($angsuran) {
            $this->regenerateReal($id_pinj);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Transaksi Reversal untuk id ' . $idt . ' dengan nominal berhasil.',
            'idtp' => $last_idtp + 1,
            'tgl_transaksi' => date('Y-m-d'),
            'id_pinkel' => $id_pinj
        ]);
    }

    public function hapus(Request $request)
    {
        $idt = $request->del_idt;
        $idtp = $request->del_idtp;
        $id_pinj = $request->del_id_pinj;

        if ($idtp != '0') {
            $trx = Transaksi::where('idtp', $idtp)->delete();

            $this->regenerateReal($id_pinj);
        } else {
            if ($id_pinj != '0') {
                $pinkel = PinjamanKelompok::where('id', $id_pinj)->update([
                    'status' => 'W'
                ]);

                $pinj_anggota = PinjamanAnggota::where('id_pinkel', $id_pinj)->update([
                    'status' => 'W'
                ]);

                $real = RealAngsuran::where('loan_id', $id_pinj)->delete();
                $trx = Transaksi::where('idtp', $idtp)->delete();
            }

            $trx = Transaksi::where('idt', $idt)->delete();
        }

        return response()->json([
            'success' => true,
            'msg' => 'Transaksi Berhasil Dihapus.'
        ]);
    }

    public function detailAngsuran($id)
    {
        $pinkel = PinjamanKelompok::where('id', $id)->with([
            'real',
            'real.trx',
            'kelompok'
        ])->first();

        return [
            'label' => '<i class="fas fa-book"></i> Detail Angsuran Kelompok ' . $pinkel->kelompok->nama_kelompok,
            'view' => view('transaksi.jurnal_angsuran.partials.detail')->with(compact('pinkel'))->render()
        ];
    }

    public function struk($id)
    {
        $data['real'] = RealAngsuran::where('id', $id)->with('trx')->firstOrFail();
        $data['ra'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id],
            ['target_pokok', '>=', $data['real']->sum_pokok]
        ])->orderBy('jatuh_tempo', 'ASC')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $data['real']->loan_id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'jpp',
            'sis_pokok'
        ])->first();
        $data['user'] = User::where('id', $data['real']->id_user)->first();
        $data['kec'] = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $data['keuangan'] = new Keuangan;

        return view('transaksi.jurnal_angsuran.dokumen.struk', $data);
    }

    public function strukMatrix($id)
    {
        $data['real'] = RealAngsuran::where('id', $id)->with('trx')->firstOrFail();
        $data['ra'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id],
            ['target_pokok', '>=', $data['real']->sum_pokok]
        ])->orderBy('jatuh_tempo', 'ASC')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $data['real']->loan_id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'jpp',
            'sis_pokok'
        ])->first();
        $data['user'] = User::where('id', $data['real']->id_user)->first();
        $data['kec'] = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $data['keuangan'] = new Keuangan;

        return view('transaksi.jurnal_angsuran.dokumen.struk_matrix', $data);
    }

    public function saldo($kode_akun)
    {
        $keuangan = new Keuangan;

        $saldo = 0;
        if (request()->get('tahun') || request()->get('bulan') || request()->get('hari')) {
            $data = [];
            $data['tahun'] = request()->get('tahun');
            $data['bulan'] = request()->get('bulan');
            $data['hari'] = request()->get('hari');

            if (strlen($data['hari']) > 0 && strlen($data['bulan']) > 0) {
                $tgl_kondisi = $data['tahun'] . '-' . $data['bulan'] . '-' . $data['hari'];
            } elseif (strlen($data['bulan']) > 0) {
                $tgl_kondisi = $data['tahun'] . '-' . $data['bulan'] . '-' . date('t', strtotime($data['tahun'] . '-' . $data['bulan']));
            } else {
                $tgl_kondisi = $data['tahun'] . '-12-31';
            }

            $saldo = $keuangan->Saldo($tgl_kondisi, $kode_akun);
        }

        return response()->json([
            'saldo' => $saldo
        ]);
    }

    public function kuitansi($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->first();
        $user = User::where('id', $trx->id_user)->first();

        $jenis = 'BKM';
        $dari = ucwords($trx->relasi);
        $oleh = ucwords(auth()->user()->namadepan . ' ' . auth()->user()->namabelakang);
        $dibayar = ucwords($trx->relasi);
        if ($trx->rekening_kredit == '1.1.01.01' or $trx->rekening_kredit == '1.1.01.02') {
            $jenis = 'BKK';
            $dari = $trx->sebutan_level_3 . " " . ucwords($trx->nama_lembaga_sort);
            $oleh = ucwords($trx->relasi);
            $dibayar = ucwords($user->namadepan . ' ' . $user->namabelakang);
        }

        $logo = $kec->logo;
        if (!file_exists('../../../assets/images/logo/' . $logo) || empty($logo)) {
            $gambar = '../../../assets/images/logo/icon-logo.png';
        } else {
            $gambar = '../../../assets/images/logo/' . $logo;
        }

        return view('transaksi.dokumen.kuitansi')->with(compact('trx', 'kec', 'jenis', 'dari', 'oleh', 'dibayar', 'gambar', 'keuangan'));
    }

    public function bkk($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $logo = $kec->logo;
        if (!file_exists('../../../assets/images/logo/' . $logo) || empty($logo)) {
            $gambar = '../../../assets/images/logo/icon-logo.png';
        } else {
            $gambar = '../../../assets/images/logo/' . $logo;
        }

        return view('transaksi.dokumen.bkk')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bkm($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $logo = $kec->logo;
        if (!file_exists('../../../assets/images/logo/' . $logo) || empty($logo)) {
            $gambar = '../../../assets/images/logo/icon-logo.png';
        } else {
            $gambar = '../../../assets/images/logo/' . $logo;
        }

        return view('transaksi.dokumen.bkm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bm($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $logo = $kec->logo;
        if (!file_exists('../../../assets/images/logo/' . $logo) || empty($logo)) {
            $gambar = '../../../assets/images/logo/icon-logo.png';
        } else {
            $gambar = '../../../assets/images/logo/' . $logo;
        }

        return view('transaksi.dokumen.bm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bkmAngsuran($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit', 'tr_idtp', 'tr_idtp.rek_kredit')->withSum('tr_idtp', 'jumlah')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', auth()->user()->lokasi]
        ])->first();

        $logo = $kec->logo;
        if (!file_exists('../../../assets/images/logo/' . $logo) || empty($logo)) {
            $gambar = '../../../assets/images/logo/icon-logo.png';
        } else {
            $gambar = '../../../assets/images/logo/' . $logo;
        }

        return view('transaksi.jurnal_angsuran.dokumen.bkm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function lpp($id)
    {
        $data['bulan'] = date('Y-m-t');
        $data['kec'] = Kecamatan::where('id', auth()->user()->lokasi)->with('kabupaten')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'jpp',
            'jasa',
            'target' => function ($query) {
                $query->where('angsuran_ke', '1');
            }
        ])->first();

        $tb_ra = 'rencana_angsuran_' . $data['kec']->id;
        $tb_real = 'real_angsuran_' . $data['kec']->id;
        $data['rencana'] = RencanaAngsuran::select(
            '*',
            DB::raw('(SELECT sum(realisasi_pokok) as rp FROM ' . $tb_real . ' WHERE ' . $tb_real . '.loan_id=' . $id . ' AND ' . $tb_real . '.tgl_transaksi<=' . $tb_ra . '.jatuh_tempo) as sum_pokok'),
            DB::raw('(SELECT sum(realisasi_jasa) as rj FROM ' . $tb_real . ' WHERE ' . $tb_real . '.loan_id=' . $id . ' AND ' . $tb_real . '.tgl_transaksi<=' . $tb_ra . '.jatuh_tempo) as sum_jasa')
        )->where([
            ['loan_id', $id],
            ['angsuran_ke', '!=', '0']
        ])->get();

        $data['laporan'] = 'LPP Kelompok ' . $data['pinkel']->kelompok->nama_kelompok;
        return view('transaksi.jurnal_angsuran.dokumen.lpp', $data);
    }

    public function generateReal($id_pinkel)
    {
        $idtp = request()->get('idtp');
        $tgl_transaksi = request()->get('tgl_transaksi');

        if ($idtp == 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Error'
            ]);
        }

        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->first();

        $transaksi = Transaksi::where([
            ['id_pinj', $pinkel->id],
            ['idtp', $idtp]
        ])->get();

        $real = RealAngsuran::where([
            ['loan_id', $id_pinkel],
            ['tgl_transaksi', '<=', $tgl_transaksi]
        ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');
        $ra = RencanaAngsuran::where([
            ['loan_id', $id_pinkel],
            ['jatuh_tempo', '<=', $tgl_transaksi],
            ['angsuran_ke', '!=', '0']
        ])->orderBy('jatuh_tempo', 'DESC');

        $alokasi_pokok = intval($pinkel->alokasi);
        $alokasi_jasa = intval($pinkel->alokasi * ($pinkel->pros_jasa / 100));

        if ($real->count() > 0) {
            $real = $real->first();
        } else {
            $real->sum_pokok = 0;
            $real->sum_jasa = 0;
            $real->saldo_pokok = $alokasi_pokok;
            $real->saldo_jasa = $alokasi_jasa;
            $real->tunggakan_pokok = 0;
            $real->tunggakan_jasa = 0;
        }

        if ($ra->count() > 0) {
            $ra = $ra->first();
        } else {
            $ra->target_pokok = 0;
            $ra->target_jasa = 0;
        }

        $tunggakan_pokok = $ra->target_pokok - $real->sum_pokok;
        if ($tunggakan_pokok <= 0) $tunggakan_pokok = 0;

        $tunggakan_jasa = $ra->target_jasa - $real->sum_jasa;
        if ($tunggakan_jasa <= 0) $tunggakan_jasa = 0;

        $insert = [
            'id' => $idtp,
            'loan_id' => $pinkel->id,
            'tgl_transaksi' => $tgl_transaksi,
            'realisasi_pokok' => 0,
            'realisasi_jasa' => 0,
            'sum_pokok' => $real->sum_pokok,
            'sum_jasa' => $real->sum_jasa,
            'saldo_pokok' => $real->saldo_pokok,
            'saldo_jasa' => $real->saldo_jasa,
            'tunggakan_pokok' => $tunggakan_pokok,
            'tunggakan_jasa' => $tunggakan_jasa,
            'lu' => date('Y-m-d H:i:s'),
            'id_user' => auth()->user()->id,
        ];

        $rek_pokok = ['1.1.03.01', '1.1.03.02', '1.1.03.03'];
        $rek_jasa = ['1.1.03.04', '1.1.03.05', '1.1.03.06', '4.1.01.01', '4.1.01.02', '4.1.01.03'];

        foreach ($transaksi as $trx) {
            if (in_array($trx->rekening_kredit, $rek_pokok)) {
                $insert['realisasi_pokok'] += $trx->jumlah;
                $insert['sum_pokok'] += $trx->jumlah;
                $insert['saldo_pokok'] -= $trx->jumlah;
                $insert['tunggakan_pokok'] -= $trx->jumlah;

                if ($insert['tunggakan_pokok'] <= 0) $insert['tunggakan_pokok'] = 0;
            }

            if (in_array($trx->rekening_kredit, $rek_jasa)) {
                $insert['realisasi_jasa'] += $trx->jumlah;
                $insert['sum_jasa'] += $trx->jumlah;
                $insert['saldo_jasa'] -= $trx->jumlah;
                $insert['tunggakan_jasa'] -= $trx->jumlah;

                if ($insert['tunggakan_jasa'] <= 0) $insert['tunggakan_jasa'] = 0;
            }
        }

        if (RealAngsuran::where([['id', $idtp], ['loan_id', $pinkel->id]])->count() > 0) {
            RealAngsuran::where([['id', $idtp], ['loan_id', $pinkel->id]])->delete();
        }

        RealAngsuran::create($insert);
        return response()->json([
            'success' => true
        ]);
    }

    public function regenerateReal($id_pinkel)
    {
        $keuangan = new Keuangan;
        if ($id_pinkel == 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Error'
            ]);
        }

        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->first();
        $transaksi = Transaksi::select(
            'idtp',
            'tgl_transaksi'
        )->where([
            ['id_pinj', $pinkel->id],
            ['idtp', '!=', '0']
        ])->with([
            'tr_idtp'
        ])->groupBy('idtp', 'tgl_transaksi')->orderBy('idtp', 'ASC')->orderBy('tgl_transaksi', 'ASC')->get();

        $alokasi_pokok = intval($pinkel->alokasi);
        $alokasi_jasa = intval($pinkel->alokasi * ($pinkel->pros_jasa / 100));

        $rek_pokok = ['1.1.03.01', '1.1.03.02', '1.1.03.03'];
        $rek_jasa = ['1.1.03.04', '1.1.03.05', '1.1.03.06', '4.1.01.01', '4.1.01.02', '4.1.01.03'];

        $sum_pokok = 0;
        $sum_jasa = 0;

        // dd($transaksi);
        RealAngsuran::where('loan_id', $pinkel->id)->delete();
        foreach ($transaksi as $trx) {
            $tgl_transaksi = $trx->tgl_transaksi;

            $insert = [
                'id' => $trx->idtp,
                'loan_id' => $pinkel->id,
                'tgl_transaksi' => $tgl_transaksi,
                'realisasi_pokok' => 0,
                'realisasi_jasa' => 0,
                'sum_pokok' => $sum_pokok,
                'sum_jasa' => $sum_jasa,
                'saldo_pokok' => $alokasi_pokok - $sum_pokok,
                'saldo_jasa' => $alokasi_jasa - $sum_jasa,
                'tunggakan_pokok' => 0,
                'tunggakan_jasa' => 0,
                'lu' => date('Y-m-d H:i:s'),
                'id_user' => auth()->user()->id,
            ];

            if ($trx->relationLoaded('tr_idtp')) {
                $ra = RencanaAngsuran::where([
                    ['loan_id', $id_pinkel],
                    ['jatuh_tempo', '<=', $tgl_transaksi],
                    ['angsuran_ke', '!=', '0']
                ])->orderBy('jatuh_tempo', 'DESC');

                if ($ra->count() > 0) {
                    $ra = $ra->first();
                } else {
                    $ra->target_pokok = 0;
                    $ra->target_jasa = 0;
                }

                foreach ($trx->tr_idtp as $tr) {
                    if (in_array($tr->rekening_kredit, $rek_pokok)) {
                        $sum_pokok += intval($tr->jumlah);

                        $tunggakan_pokok = $ra->target_pokok - $sum_pokok;
                        if ($tunggakan_pokok <= 0) $tunggakan_pokok = 0;

                        $insert['realisasi_pokok'] = $tr->jumlah;
                        $insert['sum_pokok'] = $sum_pokok;
                        $insert['saldo_pokok'] = $alokasi_pokok - $sum_pokok;
                        $insert['tunggakan_pokok'] = $tunggakan_pokok;
                    }

                    if (in_array($tr->rekening_kredit, $rek_jasa)) {
                        $sum_jasa += intval($tr->jumlah);

                        $tunggakan_jasa = $ra->target_jasa - $sum_jasa;
                        if ($tunggakan_jasa <= 0) $tunggakan_jasa = 0;

                        $insert['realisasi_jasa'] = $tr->jumlah;
                        $insert['sum_jasa'] = $sum_jasa;
                        $insert['saldo_jasa'] = $alokasi_jasa - $sum_jasa;
                        $insert['tunggakan_jasa'] = $tunggakan_jasa;
                    }
                }

                RealAngsuran::create($insert);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }
}
