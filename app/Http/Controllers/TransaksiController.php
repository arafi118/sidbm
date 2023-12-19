<?php

namespace App\Http\Controllers;

use App\Models\AkunLevel1;
use App\Models\Desa;
use App\Models\Ebudgeting;
use App\Models\Inventaris;
use App\Models\JenisTransaksi;
use App\Models\Kecamatan;
use App\Models\PinjamanAnggota;
use App\Models\PinjamanKelompok;
use App\Models\RealAngsuran;
use App\Models\Rekening;
use App\Models\RencanaAngsuran;
use App\Models\Saldo;
use App\Models\Transaksi;
use App\Models\User;
use App\Utils\Inventaris as UtilsInventaris;
use App\Utils\Keuangan;
use App\Utils\Tanggal;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Session;

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

        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        return view('transaksi.jurnal_umum.index')->with(compact('title', 'jenis_transaksi', 'kec'));
    }

    public function jurnalAngsuran()
    {
        $title = 'Jurnal Angsuran';

        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();
        if (request()->get('pinkel')) {
            $pinkel = PinjamanKelompok::where('id', request()->get('pinkel'))->with('kelompok');
            $pinkel = $pinkel->first();
        } else {
            $pinkel = '0';
        }

        return view('transaksi.jurnal_angsuran.index')->with(compact('title', 'pinkel', 'kec'));
    }

    public function ebudgeting()
    {
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $title = 'E - Budgeting';
        return view('transaksi.ebudgeting.index')->with(compact('title', 'kec'));
    }

    public function jurnalTutupBuku()
    {
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $title = 'Tutup Buku';
        return view('transaksi.tutup_buku.index')->with(compact('title', 'kec'));
    }

    public function saldoAwal($tahun)
    {
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        $tgl_pakai = $kec->tgl_pakai;
        $tahun_pakai = Tanggal::tahun($tgl_pakai);

        $saldo = [];
        $data_id = [];

        $rekening = Rekening::all();
        foreach ($rekening as $rek) {
            for ($i = $tahun_pakai; $i <= $tahun; $i++) {
                $tahun_tb = $i - 1;
                $tb = 'tb' . $tahun_tb;
                $tbk = 'tbk' . $tahun_tb;

                $saldo[] = [
                    'id' => str_replace('.', '', $rek->kode_akun) . $i . '00',
                    'kode_akun' => $rek->kode_akun,
                    'tahun' => intval($i),
                    'bulan' => '0',
                    'debit' => $rek->$tb,
                    'kredit' => $rek->$tbk
                ];

                $data_id[] = str_replace('.', '', $rek->kode_akun) . $i . '00';
            }
        }

        Saldo::whereIn('id', $data_id)->delete();
        Saldo::insert($saldo);

        echo "<script>window.close();</script>";
    }

    public function saldoTutupBuku(Request $request)
    {
        $keuangan = new Keuangan;
        $tgl_pakai = $request->tgl_pakai ?: date('Y-m-d');
        $tahun = $request->tahun;
        $tahun_lalu = $tahun - 1;
        $tahun_pakai = Tanggal::tahun($tgl_pakai);
        $bulan = date('m');
        if ($tahun < date('Y')) {
            $bulan = 12;
        }

        $akun1 = AkunLevel1::where('lev1', '<=', '3')->with([
            'akun2',
            'akun2.akun3',
            'akun2.akun3.rek',
            'akun2.akun3.rek.saldo' => function ($query) use ($tahun, $bulan) {
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', $bulan]
                ]);
            },
        ])->orderBy('kode_akun', 'ASC')->get();

        $surplus = Rekening::where([
            ['lev1', '>=', '4']
        ])->with([
            'saldo' => function ($query) use ($tahun, $bulan) {
                $query->where([
                    ['tahun', $tahun],
                    ['bulan', $bulan]
                ]);
            }
        ])->orderBy('kode_akun', 'ASC')->get();

        $total_riwayat = ($tahun + 1) - $tahun_pakai;
        $jumlah_riwayat = count(Saldo::select('tahun')->whereRaw('LENGTH(kode_akun) = 9')->where('bulan', '0')->whereBetween('tahun', [$tahun_pakai, $tahun])->groupBy('tahun')->get());

        $tgl_kondisi = $tahun . '-' . $bulan . '-' . date('t', strtotime($tahun . '-' . $bulan . '-01'));
        return response()->json([
            'success' => true,
            'view' => view('transaksi.tutup_buku.partials.saldo')->with(compact('akun1', 'surplus', 'tgl_kondisi', 'tahun', 'tahun_lalu', 'total_riwayat', 'jumlah_riwayat'))->render()
        ]);
    }

    public function simpanTutupBuku(Request $request)
    {
        $keuangan = new Keuangan;

        $tahun = $request->tahun;
        $bulan = date('m');
        if ($tahun < date('Y')) {
            $bulan = 12;
        }
        $tgl_kondisi = $tahun . '-' . $bulan . '-' . date('t', strtotime($tahun . '-' . $bulan . '-01'));

        $success = false;
        $migrasi_saldo = false;
        if ($request->pembagian_laba == 'false') {
            $jumlah_riwayat = $request->jumlah_riwayat;
            $total_riwayat = $request->total_riwayat;

            if ($jumlah_riwayat < $total_riwayat) {
                $migrasi_saldo = true;
            }

            $tahun_tb = $tahun + 1;
            $kode_rekening = Rekening::with([
                'kom_saldo' => function ($query) use ($tahun, $bulan) {
                    $query->where('tahun', $tahun)->where(function ($query) use ($bulan) {
                        $query->where('bulan', '0')->orwhere('bulan', $bulan);
                    });
                }
            ])->get();

            $data_id = [];
            $saldo_tutup_buku = [];
            foreach ($kode_rekening as $rek) {
                $saldo_awal_debit = 0;
                $saldo_awal_kredit = 0;
                $debit = 0;
                $kredit = 0;

                $bulan_tb = '0';
                if ($rek->lev1 >= 4) {
                    foreach ($rek->kom_saldo as $saldo) {
                        if ($saldo->bulan == 0) {
                            if ($saldo->debit > 0) $saldo_awal_debit = $saldo->debit;
                            if ($saldo->kredit > 0) $saldo_awal_kredit = $saldo->kredit;
                        } else {
                            if ($saldo->debit != 0) $debit = $saldo->debit;
                            if ($saldo->kredit != 0) $kredit = $saldo->kredit;
                        }
                    }

                    $saldo_debit = $debit;
                    $saldo_kredit = $kredit;

                    $id = str_replace('.', '', $rek->kode_akun) . $tahun . '13';
                    if ($saldo_debit + $saldo_kredit != 0) {
                        $saldo_tutup_buku[] = [
                            'id' => $id,
                            'kode_akun' => $rek->kode_akun,
                            'tahun' => $tahun,
                            'bulan' => 13,
                            'debit' => $saldo_debit,
                            'kredit' => $saldo_kredit
                        ];

                        $data_id[] = $id;
                    }
                }

                if ($rek->lev1 < 4 && $rek->kode_akun != '3.2.02.01') {
                    foreach ($rek->kom_saldo as $saldo) {
                        if ($saldo->bulan == 0) {
                            if ($saldo->debit > 0) $saldo_awal_debit = $saldo->debit;
                            if ($saldo->kredit > 0) $saldo_awal_kredit = $saldo->kredit;
                        } else {
                            if ($saldo->debit > 0) $debit = $saldo->debit;
                            if ($saldo->kredit > 0) $kredit = $saldo->kredit;
                        }
                    }
                }

                $saldo_debit = $saldo_awal_debit + $debit;
                $saldo_kredit = $saldo_awal_kredit + $kredit;

                $id = str_replace('.', '', $rek->kode_akun) . $tahun_tb . '00';
                $saldo_tutup_buku[] = [
                    'id' => $id,
                    'kode_akun' => $rek->kode_akun,
                    'tahun' => $tahun_tb,
                    'bulan' => $bulan_tb,
                    'debit' => $saldo_debit,
                    'kredit' => $saldo_kredit
                ];

                $data_id[] = $id;
            }

            Saldo::whereIn('id', $data_id)->delete();
            Saldo::insert($saldo_tutup_buku);

            $success = true;
        }

        $surplus = $keuangan->laba_rugi($tahun . '-13-00');

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with([
            'saldo' => function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);
            },
        ])->first();
        $rekening = Rekening::where('kode_akun', 'like', '2.1.04%')->get();
        $desa = Desa::where('kd_kec', $kec->kd_kec)->with([
            'saldo' => function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);
            },
            'sebutan_desa'
        ])->get();

        $title = 'Pembagian Laba';
        return view('transaksi.tutup_buku.tutup_buku')->with(compact('title', 'kec', 'surplus', 'rekening', 'desa', 'tgl_kondisi', 'tahun', 'migrasi_saldo', 'success'));
    }

    public function simpanAlokasiLaba(Request $request)
    {
        $data = $request->only([
            "surplus",
            "masyarakat",
            "total_laba_bagian_masyarakat",
            "desa",
            "total_laba_bagian_desa",
            "total_laba_bagian_penyerta_modal",
            "laba_ditahan",
        ]);

        $tanggal = $request->tgl_kondisi ?: date('Y-m-d');
        $tahun = Tanggal::tahun($tanggal);
        $tahun_tb = $tahun + 1;
        $bulan = Tanggal::bulan($tanggal);

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with([
            'desa',
            'desa.saldo' => function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);
            },
            'saldo' => function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun);
            }
        ])->first();
        $desa = $kec->desa;
        $rekening = Rekening::with([
            'kom_saldo' => function ($query) use ($tahun, $bulan) {
                $query->where('tahun', $tahun)->where(function ($query) use ($bulan) {
                    $query->where('bulan', '0')->orwhere('bulan', $bulan);
                });
            }
        ])->get();

        $alokasi_laba = [
            '2.1.04.01' => str_replace(',', '', str_replace('.00', '', $data['total_laba_bagian_masyarakat'])),
            '2.1.04.02' => str_replace(',', '', str_replace('.00', '', $data['total_laba_bagian_desa'])),
            '2.1.04.03' => str_replace(',', '', str_replace('.00', '', $data['total_laba_bagian_penyerta_modal'])),
            '3.2.01.01' => 0
        ];

        $laba_ditahan = $data['laba_ditahan']; // Ditambahkan ke 3.2.01.01
        foreach ($laba_ditahan as $key => $val) {
            $value = str_replace(',', '', str_replace('.00', '', $val));
            $alokasi_laba['3.2.01.01'] += $value;
        }

        $pembagian_laba_desa = $data['desa'];
        $pembagian_laba_ditahan = $data['laba_ditahan'];
        $pembagian_laba_masyarakat = $data['masyarakat'];

        $data_id = [];
        $saldo_tutup_buku = [];
        foreach ($desa as $d) {
            $id = $d->kd_desa . $tahun_tb . 0;
            $saldo_tutup_buku[] = [
                'id' => $id,
                'kode_akun' => $d->kode_desa,
                'tahun' => $tahun_tb,
                'bulan' => '0',
                'debit' => $d->saldo->kredit,
                'kredit' => str_replace(',', '', str_replace('.00', '', $pembagian_laba_desa[$d->kd_desa]))
            ];

            $data_id[] = $id;
        }

        foreach ($kec->saldo as $saldo) {
            $urut = substr($saldo->id, -1);

            $id = str_replace('.', '', $kec->kd_kec) . $tahun_tb . 0 . $urut;
            if ($urut <= 3) {
                $saldo_tutup_buku[] = [
                    'id' => $id,
                    'kode_akun' => $kec->kd_kec,
                    'tahun' => $tahun_tb,
                    'bulan' => '0',
                    'debit' => $saldo->kredit,
                    'kredit' => str_replace(',', '', str_replace('.00', '', $pembagian_laba_masyarakat[$urut]))
                ];
            } else {
                $saldo_tutup_buku[] = [
                    'id' => $id,
                    'kode_akun' => $kec->kd_kec,
                    'tahun' => $tahun_tb,
                    'bulan' => '0',
                    'debit' => $saldo->kredit,
                    'kredit' => str_replace(',', '', str_replace('.00', '', $pembagian_laba_ditahan[$urut]))
                ];
            }

            $data_id[] = $id;
        }

        foreach ($rekening as $rek) {
            $saldo_awal_debit = 0;
            $saldo_awal_kredit = 0;
            $debit = 0;
            $kredit = 0;

            if ($rek->lev1 < 4 && $rek->kode_akun != '3.2.02.01') {
                foreach ($rek->kom_saldo as $saldo) {
                    if ($saldo->bulan == 0) {
                        if ($saldo->debit > 0) $saldo_awal_debit = $saldo->debit;
                        if ($saldo->kredit > 0) $saldo_awal_kredit = $saldo->kredit;
                    } else {
                        if ($saldo->debit > 0) $debit = $saldo->debit;
                        if ($saldo->kredit > 0) $kredit = $saldo->kredit;
                    }
                }
            }

            $saldo_debit = $saldo_awal_debit + $debit;
            $saldo_kredit = $saldo_awal_kredit + $kredit;

            if (in_array($rek->kode_akun, array_keys($alokasi_laba))) {
                $id = str_replace('.', '', $rek->kode_akun) . $tahun_tb . '00';
                $saldo_kredit += $alokasi_laba[$rek->kode_akun];
                $saldo_tutup_buku[] = [
                    'id' => $id,
                    'kode_akun' => $rek->kode_akun,
                    'tahun' => $tahun_tb,
                    'bulan' => '0',
                    'debit' => $saldo_debit,
                    'kredit' => $saldo_kredit
                ];

                $data_id[] = $id;
            }

            if ($rek->lev1 >= 4) {
                $id = str_replace('.', '', $rek->kode_akun) . $tahun_tb . '00';
                $saldo_tutup_buku[] = [
                    'id' => $id,
                    'kode_akun' => $rek->kode_akun,
                    'tahun' => $tahun_tb,
                    'bulan' => '0',
                    'debit' => 0,
                    'kredit' => 0
                ];

                $data_id[] = $id;
            }
        }

        Saldo::whereIn('id', $data_id)->delete();
        Saldo::insert($saldo_tutup_buku);

        return response()->json([
            'success' => true,
            'msg' => 'Tutup Buku Tahun ' . $tahun . ' berhasil.'
        ]);
    }

    public function formAnggaran(Request $request)
    {
        $data = $request->only(['tahun', 'bulan']);

        $validate = Validator::make($data, [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $cek = Ebudgeting::where([
            ['tahun', $request->tahun],
            ['bulan', $request->bulan]
        ])->orderBy('bulan', 'ASC')->orderBy('kode_akun', 'ASC');

        $jumlah = $cek->count();

        if ($jumlah > 0) {
            $akun1 = AkunLevel1::where('lev1', '>=', '4')->with([
                'akun2',
                'akun2.akun3',
                'akun2.akun3.rek',
                'akun2.akun3.rek.eb',
            ])->orderBy('kode_akun', 'ASC')->get();
        } else {
            $akun1 = AkunLevel1::where('lev1', '>=', '4')->with([
                'akun2',
                'akun2.akun3',
                'akun2.akun3.rek'
            ])->orderBy('kode_akun', 'ASC')->get();
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        return response()->json([
            'success' => true,
            'view' => view('transaksi.ebudgeting.create')->with(compact('akun1', 'jumlah', 'tahun', 'bulan'))->render()
        ]);
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
        $keuangan = new Keuangan;

        $tgl_transaksi = Tanggal::tglNasional($request->tgl_transaksi);
        $kec = Kecamatan::where('id', Session::get('lokasi'))->first();

        if (strtotime($tgl_transaksi) < strtotime($kec->tgl_pakai)) {
            return response()->json([
                'success' => false,
                'msg' => 'Tanggal transaksi tidak boleh sebelum Tanggal Pakai Aplikasi'
            ]);
        }

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
                'lokasi' => Session::get('lokasi'),
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
                $transaksi = Transaksi::create($trx_penghapusan);
                Inventaris::where('id', $id_inv)->update($update_inventaris);
                Inventaris::create($insert_inventaris);
            } else {
                $transaksi = Transaksi::create($trx_penghapusan);
                Inventaris::where('id', $id_inv)->update($update_sts_inventaris);
            }

            $msg = 'Penghapusan ' . $request->unit . ' unit ' . $barang . ' karena ' . $status;
            if ($status == 'dijual') {
                $transaksi = Transaksi::create($trx_penjualan);
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
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->harga_satuan)) * $request->jumlah,
                    'urutan' => 0,
                    'id_user' => auth()->user()->id,
                ];

                $inventaris = [
                    'lokasi' => Session::get('lokasi'),
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

        $trx = Transaksi::where('idt', $transaksi->idt)->with([
            'rek_debit', 'rek_kredit'
        ])->first();
        $view = view('transaksi.jurnal_umum.partials.notifikasi')->with(compact('trx', 'keuangan'))->render();
        return response()->json([
            'success' => true,
            'msg' => $msg,
            'view' => $view
        ]);
    }

    public function angsuran(Request $request)
    {
        DB::beginTransaction();

        try {
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

            $request->total_pokok_anggota = str_replace(',', '', str_replace('.00', '', $request->total_pokok_anggota));
            $request->total_jasa_anggota = str_replace(',', '', str_replace('.00', '', $request->total_jasa_anggota));
            $request->total_denda_anggota = str_replace(',', '', str_replace('.00', '', $request->total_denda_anggota));

            if ($request->pokok <= 0 && $request->jasa <= 0 && $request->denda <= 0) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Total Bayar tidak boleh nol'
                ]);
            }

            $tgl_transaksi = Tanggal::tglNasional($request->tgl_transaksi);

            $pinkel = PinjamanKelompok::where('id', $request->id)->with([
                'kelompok',
                'pinjaman_anggota',
                'saldo' => function ($query) use ($request, $tgl_transaksi) {
                    $query->where([
                        ['loan_id', $request->id],
                        ['tgl_transaksi', '<=', $tgl_transaksi]
                    ]);
                },
                'target' => function ($query) use ($request, $tgl_transaksi) {
                    $query->where([
                        ['loan_id', $request->id],
                        ['jatuh_tempo', '<=', $tgl_transaksi]
                    ]);
                }
            ])->first();
            $pinjaman_anggota = $pinkel->pinjaman_anggota;

            $sum_pokok = 0;
            $sum_jasa = 0;
            if ($pinkel->saldo) {
                $sum_pokok = $pinkel->saldo->sum_pokok;
                $sum_jasa = $pinkel->saldo->sum_jasa;
            }

            $target_pokok = 0;
            $target_jasa = 0;
            if ($pinkel->target) {
                $target_pokok = $pinkel->target->target_pokok;
                $target_jasa = $pinkel->target->target_jasa;
            }

            $tunggakan_jasa    = $target_jasa - $sum_jasa;
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

            $_pokok = floatval($request->pokok) - floatval($request->total_pokok_anggota);
            $_jasa = floatval($request->jasa) - floatval($request->total_jasa_anggota);
            $_denda = floatval($request->denda) - floatval($request->total_denda_anggota);

            if (strtotime($tgl_transaksi) < strtotime($request->tgl_pakai_aplikasi)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Tanggal transaksi tidak boleh sebelum Tanggal Pakai Aplikasi'
                ]);
            }

            $transaksi = [];

            $last_idtp = Transaksi::where('idtp', '!=', '0')->max('idtp');
            $idtp = $last_idtp + 1;
            if ($request->pokok > 0) {
                $transaksi[] = [
                    'tgl_transaksi' => (string) $tgl_transaksi,
                    'rekening_debit' => (string) $kas_umum,
                    'rekening_kredit' => (string) $poko_kredit,
                    'idtp' => $idtp,
                    'id_pinj' => $pinkel->id,
                    'id_pinj_i' => '0',
                    'keterangan_transaksi' => (string) 'Angs. (P) ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')' . ' [' . $pinkel->kelompok->d->nama_desa . ']',
                    'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->pokok)),
                    'urutan' => '0',
                    'id_user' => auth()->user()->id
                ];
            }

            if ($request->jasa > 0) {
                // if ($pinkel->jenis_pp == '1') {
                //     $jasa_kredit = '1.1.03.04';
                // } elseif ($pinkel->jenis_pp == '2') {
                //     $jasa_kredit = '1.1.03.05';
                // } else {
                //     $jasa_kredit = '1.1.03.06';
                // }

                $transaksi[] = [
                    'tgl_transaksi' => (string) $tgl_transaksi,
                    'rekening_debit' => (string) $kas_umum,
                    'rekening_kredit' => (string) $jasa_kredit,
                    'idtp' => $idtp,
                    'id_pinj' => $pinkel->id,
                    'id_pinj_i' => '0',
                    'keterangan_transaksi' => (string) 'Angs. (J) ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')' . ' [' . $pinkel->kelompok->d->nama_desa . ']',
                    'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->jasa)),
                    'urutan' => '0',
                    'id_user' => auth()->user()->id
                ];

                // if ($request->jasa < $tunggakan_jasa) {
                // } else {
                //     $angs_jasa = ($request->jasa - $tunggakan_jasa > 0) ? ($request->jasa - $tunggakan_jasa) : $request->jasa;
                //     $transaksi[] = [
                //         'tgl_transaksi' => (string) $tgl_transaksi,
                //         'rekening_debit' => (string) $kas_umum,
                //         'rekening_kredit' => (string) $jasa_kredit,
                //         'idtp' => $idtp,
                //         'id_pinj' => $pinkel->id,
                //         'id_pinj_i' => '0',
                //         'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' - [' . $pinkel->kelompok->d->nama_desa . '] (J)',
                //         'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                //         'jumlah' => str_replace(',', '', str_replace('.00', '', $angs_jasa)),
                //         'urutan' => '0',
                //         'id_user' => auth()->user()->id
                //     ];

                //     if ($request->jasa - $angs_jasa > 0) {
                //         $piutang_kredit = $jasa_kredit;
                //         if ($pinkel->jenis_pp == '1') {
                //             $piutang_kredit = '1.1.03.04';
                //         } elseif ($pinkel->jenis_pp == '2') {
                //             $piutang_kredit = '1.1.03.05';
                //         } else {
                //             $piutang_kredit = '1.1.03.06';
                //         }

                //         $piutang_jasa = $request->jasa - $angs_jasa;
                //         $transaksi[] = [
                //             'tgl_transaksi' => (string) $tgl_transaksi,
                //             'rekening_debit' => (string) $kas_umum,
                //             'rekening_kredit' => (string) $piutang_kredit,
                //             'idtp' => $idtp,
                //             'id_pinj' => $pinkel->id,
                //             'id_pinj_i' => '0',
                //             'keterangan_transaksi' => (string) 'Angs. ' . $pinkel->kelompok->nama_kelompok . ' - [' . $pinkel->kelompok->d->nama_desa . '] (J)',
                //             'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                //             'jumlah' => str_replace(',', '', str_replace('.00', '', $piutang_jasa)),
                //             'urutan' => '0',
                //             'id_user' => auth()->user()->id
                //         ];
                //     }
                // }
            }

            if ($request->denda > 0) {
                $transaksi[] = [
                    'tgl_transaksi' => (string) $tgl_transaksi,
                    'rekening_debit' => (string) $kas_umum,
                    'rekening_kredit' => (string) $dend_kredit,
                    'idtp' => $idtp,
                    'id_pinj' => $pinkel->id,
                    'id_pinj_i' => '0',
                    'keterangan_transaksi' => (string) 'Denda. ' . $pinkel->kelompok->nama_kelompok . ' (' . $pinkel->id . ')' . ' [' . $pinkel->kelompok->d->nama_desa . ']',
                    'relasi' => (string) $pinkel->kelompok->nama_kelompok,
                    'jumlah' => str_replace(',', '', str_replace('.00', '', $request->denda)),
                    'urutan' => '0',
                    'id_user' => auth()->user()->id
                ];
            }
            Transaksi::insert($transaksi);

            $jasa_pinjaman = $pinkel->pros_jasa / 100 * $pinkel->alokasi;
            foreach ($pinjaman_anggota as $pa) {
                $pokok_anggota = 0;
                if ($request->pokok_anggota) {
                    if ($request->pokok_anggota[$pa->id]) {
                        $pokok_anggota = floatval(str_replace(',', '', str_replace('.00', '', $request->pokok_anggota[$pa->id])));
                    }
                }

                $jasa_anggota = 0;
                if ($request->jasa_anggota) {
                    if ($request->jasa_anggota[$pa->id]) {
                        $jasa_anggota = str_replace(',', '', str_replace('.00', '', $request->jasa_anggota[$pa->id]));
                    }
                }

                if ($pokok_anggota <= 0) {
                    $pros_pokok_anggota = round(($pa->alokasi / $pinkel->alokasi) * 100, 2);
                    $pokok_anggota = round(($pros_pokok_anggota / 100) * $_pokok, 2);
                }

                if ($jasa_anggota <= 0) {
                    $pros_jasa_anggota = round((($pa->pros_jasa / 100 * $pa->alokasi) / $jasa_pinjaman) * 100, 2);
                    $jasa_anggota = round(($pros_jasa_anggota / 100) * $_jasa, 2);
                }

                $kom_pokok = json_decode($pa->kom_pokok, true);
                $kom_jasa = json_decode($pa->kom_jasa, true);

                if (is_array($kom_pokok)) {
                    $kom_pokok[$idtp] = $pokok_anggota;
                } else {
                    $kom_pokok = [];
                    $kom_pokok[$idtp] = $pokok_anggota;
                }

                if (is_array($kom_jasa)) {
                    $kom_jasa[$idtp] = $jasa_anggota;
                } else {
                    $kom_jasa = [];
                    $kom_jasa[$idtp] = $jasa_anggota;
                }

                PinjamanAnggota::where('id', $pa->id)->update([
                    'kom_pokok' => $kom_pokok,
                    'kom_jasa' => $kom_jasa
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'msg' => 'Angsuran kelompok ' . $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] berhasil diposting',
                'id_pinkel' => $pinkel->id,
                'idtp' => $idtp,
                'tgl_transaksi' => $tgl_transaksi
            ]);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function notifikasi($idtp)
    {
        $trx = Transaksi::where('idtp', $idtp)->first();
        $view = view('transaksi.jurnal_angsuran.partials.notif', [
            'idtp' => $idtp,
            'id_pinkel' => $trx->id_pinj,
            'idt' => $trx->idt
        ])->render();

        return response()->json([
            'view' => $view
        ]);
    }

    public function simpanAnggaran(Request $request)
    {
        $data = $request->only(['tahun', 'bulan', 'jumlah']);
        $validate = Validator::make($data, [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_MOVED_PERMANENTLY);
        }

        $insert = [];
        foreach ($request->jumlah as $kode_akun => $nominal) {
            $insert[] = [
                'kode_akun' => $kode_akun,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'jumlah' => str_replace(',', '', $nominal)
            ];
        }

        Ebudgeting::where([
            ['tahun', $request->tahun],
            ['bulan', $request->bulan],
        ])->delete();
        Ebudgeting::insert($insert);

        $nama_bulan = Tanggal::namaBulan($request->tahun . '-' . $request->bulan . '-01');

        return response()->json([
            'success' => true,
            'msg' => 'Rencana Anggaran bulan ' . $nama_bulan . ' berhasil disimpan.'
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
            $rek1 = Rekening::all();

            $rek2 = Rekening::all();

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
            ])->whereNotNull('tgl_beli')->where(function ($query) {
                $query->where('status', 'Baik')->orwhere('status', 'Rusak');
            })->get();
            return view('transaksi.jurnal_umum.partials.form_hapus_inventaris')->with(compact('inventaris', 'tgl_transaksi'));
        } else {
            if (Keuangan::startWith($disimpan_ke, '1.2.01') || Keuangan::startWith($disimpan_ke, '1.2.03')) {
                $kuitansi = false;
                $relasi = false;
                $files = 'bm';
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
                $files = 'bm';
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
        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->with('kelompok')->withSum([
            'rencana' => function ($query) {
                $query->where('jatuh_tempo', '<=', date('Y-m-t'));
            }
        ], 'wajib_pokok')->withSum([
            'rencana' => function ($query) {
                $query->where('jatuh_tempo', '<=', date('Y-m-t'));
            }
        ], 'wajib_jasa')->firstOrFail();
        $real = RealAngsuran::where([
            ['loan_id', $id_pinkel],
            ['tgl_transaksi', '<=', date('Y-m-d')]
        ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');

        $alokasi_jasa = $pinkel->alokasi * ($pinkel->pros_jasa / 100);

        if ($real->count() > 0) {
            $real = $real->first();
        } else {
            $real->sum_pokok = 0;
            $real->sum_jasa = 0;
        }

        $target_pokok = $pinkel->rencana_sum_wajib_pokok;
        $target_jasa = $pinkel->rencana_sum_wajib_jasa;

        $saldo_pokok = ($target_pokok - $real->sum_pokok > 0) ? $target_pokok - $real->sum_pokok : 0;
        $saldo_jasa = ($target_jasa - $real->sum_jasa > 0) ? $target_jasa - $real->sum_jasa : 0;

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

    public function formAnggota($id_pinkel)
    {
        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->with([
            'kelompok',
            'pinjaman_anggota',
            'pinjaman_anggota.anggota',
        ])->firstOrFail();

        return response()->json([
            'success' => true,
            'title' => 'Angsuran per Anggota Kelompok ' . $pinkel->kelompok->nama_kelompok . ' - Loan ID. ' . $pinkel->id,
            'view' => view('transaksi.jurnal_angsuran.partials.angsuran_anggota')->with(compact('pinkel'))->render()
        ]);
    }

    public function targetAngsuran($id_pinkel)
    {
        if (request()->get('tanggal')) {
            $tanggal = request()->get('tanggal');
            $tahun = date('Y', strtotime(Tanggal::tglNasional($tanggal)));
            $bulan = date('m', strtotime(Tanggal::tglNasional($tanggal)));
            $hari = date('t', strtotime(Tanggal::tglNasional($tanggal)));

            $pinkel = PinjamanKelompok::where('id', $id_pinkel)->with('kelompok')->withSum([
                'rencana' => function ($query) use ($tahun, $bulan, $hari) {
                    $query->where('jatuh_tempo', '<=', $tahun . '-' . $bulan . '-' . $hari);
                }
            ], 'wajib_pokok')->withSum([
                'rencana' => function ($query) use ($tahun, $bulan, $hari) {
                    $query->where('jatuh_tempo', '<=', $tahun . '-' . $bulan . '-' . $hari);
                }
            ], 'wajib_jasa')->firstOrFail();
        } else {
            $pinkel = PinjamanKelompok::where('id', $id_pinkel)->with('kelompok')->withSum([
                'rencana' => function ($query) {
                    $query->where('jatuh_tempo', '<=', date('Y-m-t'));
                }
            ], 'wajib_pokok')->withSum([
                'rencana' => function ($query) {
                    $query->where('jatuh_tempo', '<=', date('Y-m-t'));
                }
            ], 'wajib_jasa')->firstOrFail();
        }

        $real = RealAngsuran::where([
            ['loan_id', $id_pinkel],
            ['tgl_transaksi', '<=', date('Y-m-d')]
        ])->orderBy('tgl_transaksi', 'DESC')->orderBy('id', 'DESC');

        if ($real->count() > 0) {
            $real = $real->first();
        } else {
            $real->sum_pokok = 0;
            $real->sum_jasa = 0;
        }

        $target_pokok = $pinkel->rencana_sum_wajib_pokok;
        $target_jasa = $pinkel->rencana_sum_wajib_jasa;

        $saldo_pokok = ($target_pokok - $real->sum_pokok > 0) ? $target_pokok - $real->sum_pokok : 0;
        $saldo_jasa = ($target_jasa - $real->sum_jasa > 0) ? $target_jasa - $real->sum_jasa : 0;

        return response()->json([
            'saldo_pokok' => $saldo_pokok,
            'saldo_jasa' => $saldo_jasa
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
            $data['judul'] = 'Laporan Harian';
            $data['sub_judul'] = 'Tanggal ' . $hari . ' ' . Tanggal::namaBulan($tgl) . ' ' . Tanggal::tahun($tgl);
            $data['tgl'] = Tanggal::tglLatin($tgl);
            $awal_bulan = date('Y-m-d', strtotime('-1 day', strtotime($tgl)));
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

        $data['is_dir'] = (auth()->guard('web')->user()->level == 1 && (auth()->guard('web')->user()->jabatan == 1 || auth()->guard('web')->user()->jabatan == 3)) ? true : false;
        $data['is_ben'] = (auth()->guard('web')->user()->level == 1 && (auth()->guard('web')->user()->jabatan == 3)) ? true : false;

        $data['rek'] = Rekening::where('kode_akun', $data['kode_akun'])->first();
        $data['transaksi'] = Transaksi::where('tgl_transaksi', 'LIKE', '%' . $tgl . '%')->where(function ($query) use ($data) {
            $query->where('rekening_debit', $data['kode_akun'])->orwhere('rekening_kredit', $data['kode_akun']);
        })->with('user')->orderBy('tgl_transaksi', 'ASC')->orderBy('urutan', 'ASC')->orderBy('idt', 'ASC')->get();

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
        $idtp_baru = $last_idtp + 1;

        $angsuran = false;
        if ($idtp != '0') {
            $trx_reversal = [];
            $pinkel = PinjamanKelompok::where('id', $id_pinj)->with('pinjaman_anggota')->first();
            $transaksi = Transaksi::where('idtp', $idtp)->orderBy('idtp', 'ASC')->get();
            foreach ($transaksi as $trx) {
                $trx_reversal[] = [
                    'tgl_transaksi' => (string) date('Y-m-d'),
                    'rekening_debit' => (string) $trx->rekening_debit,
                    'rekening_kredit' => (string) $trx->rekening_kredit,
                    'idtp' => $idtp_baru,
                    'id_pinj' => $trx->id_pinj,
                    'id_pinj_i' => $trx->id_pinj_i,
                    'keterangan_transaksi' => (string) 'KOREKSI idt (' . $idt . ') : ' . $trx->keterangan_transaksi,
                    'relasi' => (string) $trx->relasi,
                    'jumlah' => ($trx->jumlah * -1),
                    'urutan' => $trx->urutan,
                    'id_user' => auth()->user()->id
                ];
            }
            $reversal = Transaksi::insert($trx_reversal);

            $pinjaman_anggota = $pinkel->pinjaman_anggota;
            foreach ($pinjaman_anggota as $pa) {
                $kom_pokok = json_decode($pa->kom_pokok, true);
                $kom_jasa = json_decode($pa->kom_jasa, true);

                if (is_array($kom_pokok)) {
                    $kom_pokok[$idtp_baru] = $kom_pokok[$idtp] * -1;
                } else {
                    $kom_pokok = [];
                }

                if (is_array($kom_jasa)) {
                    $kom_jasa[$idtp_baru] = $kom_jasa[$idtp] * -1;
                } else {
                    $kom_jasa = [];
                }

                PinjamanAnggota::where('id', $pa->id)->update([
                    'kom_pokok' => $kom_pokok,
                    'kom_jasa' => $kom_jasa
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
            $this->regenerateReal($pinkel);
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
            $pinkel = PinjamanKelompok::where('id', $id_pinj)->with('pinjaman_anggota')->first();

            $pinjaman_anggota = $pinkel->pinjaman_anggota;
            foreach ($pinjaman_anggota as $pa) {
                $kom_pokok = json_decode($pa->kom_pokok, true);
                $kom_jasa = json_decode($pa->kom_jasa, true);

                if (is_array($kom_pokok)) {
                    unset($kom_pokok[$idtp]);
                } else {
                    $kom_pokok = [];
                }

                if (is_array($kom_jasa)) {
                    unset($kom_jasa[$idtp]);
                } else {
                    $kom_jasa = [];
                }

                PinjamanAnggota::where('id', $pa->id)->update([
                    'kom_pokok' => $kom_pokok,
                    'kom_jasa' => $kom_jasa
                ]);
            }

            $this->regenerateReal($pinkel);
        } else {
            if ($id_pinj != '0') {
                $pinkel = PinjamanKelompok::where('id', $id_pinj)->update([
                    'status' => 'W'
                ]);

                $pinj_anggota = PinjamanAnggota::where('id_pinkel', $id_pinj)->update([
                    'status' => 'W',
                    'kom_pokok' => 0,
                    'kom_jasa' => 0
                ]);

                $real = RealAngsuran::where('loan_id', $id_pinj)->delete();
                $trx = Transaksi::where('id_pinj', $id_pinj)->delete();
            } else {
                $trx = Transaksi::where('idt', $idt)->delete();
            }
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
        $data['real'] = RealAngsuran::where('id', $id)->with('trx', 'trx.user')->firstOrFail();
        // $data['ra'] = RencanaAngsuran::where([
        //     ['loan_id', $data['real']->loan_id],
        //     ['target_pokok', '>=', $data['real']->sum_pokok]
        // ])->orderBy('jatuh_tempo', 'ASC')->first();
        $data['ra'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id]
        ])->orderBy('jatuh_tempo', 'DESC')->first();
        $data['ra_bulan_ini'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id],
            ['jatuh_tempo', '<=', date('Y-m-t', strtotime($data['real']->tgl_transaksi))]
        ])->orderBy('jatuh_tempo', 'DESC')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $data['real']->loan_id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'jpp',
            'sis_pokok'
        ])->first();
        $data['user'] = User::where('id', $data['real']->id_user)->first();
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $data['keuangan'] = new Keuangan;

        return view('transaksi.jurnal_angsuran.dokumen.struk', $data);
    }

    public function strukMatrix($id)
    {
        $data['real'] = RealAngsuran::where('id', $id)->with('trx', 'trx.user')->firstOrFail();
        // $data['ra'] = RencanaAngsuran::where([
        //     ['loan_id', $data['real']->loan_id],
        //     ['target_pokok', '>=', $data['real']->sum_pokok]
        // ])->orderBy('jatuh_tempo', 'ASC')->first();
        $data['ra'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id]
        ])->orderBy('jatuh_tempo', 'DESC')->first();
        $data['ra_bulan_ini'] = RencanaAngsuran::where([
            ['loan_id', $data['real']->loan_id],
            ['jatuh_tempo', '<=', date('Y-m-t', strtotime($data['real']->tgl_transaksi))]
        ])->orderBy('jatuh_tempo', 'DESC')->first();
        $data['pinkel'] = PinjamanKelompok::where('id', $data['real']->loan_id)->with([
            'kelompok',
            'kelompok.d',
            'kelompok.d.sebutan_desa',
            'jpp',
            'sis_pokok'
        ])->first();
        $data['user'] = User::where('id', $data['real']->id_user)->first();
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
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

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->first();
        $user = User::where('id', $trx->id_user)->first();

        $jenis = 'BKM';
        $dari = ucwords($trx->relasi);
        $oleh = ucwords(auth()->user()->namadepan . ' ' . auth()->user()->namabelakang);
        $dibayar = ucwords($trx->relasi);
        if ($trx->rekening_kredit == '1.1.01.01' or ($keuangan->startWith($trx->rekening_kredit, '1.1.02') || $keuangan->startWith($trx->rekening_kredit, '1.1.01'))) {
            $jenis = 'BKK';
            $dari = $kec->sebutan_level_3 . " " . ucwords($kec->nama_lembaga_sort);
            $oleh = ucwords($trx->relasi);
            $dibayar = ucwords($user->namadepan . ' ' . $user->namabelakang);
        }

        $logo = $kec->logo;
        if (empty($logo)) {
            $gambar = '/storage/logo/1.png';
        } else {
            $gambar = '/storage/logo/' . $logo;
        }

        return view('transaksi.dokumen.kuitansi')->with(compact('trx', 'kec', 'jenis', 'dari', 'oleh', 'dibayar', 'gambar', 'keuangan'));
    }

    public function bkk($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $logo = $kec->logo;
        if (!(Storage::exists('logo/' . $kec->logo)) || empty($logo)) {
            $gambar = '/storage/logo/1.png';
        } else {
            $gambar = '/storage/logo/' . $logo;
        }

        return view('transaksi.dokumen.bkk')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bkm($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $logo = $kec->logo;
        if (!(Storage::exists('logo/' . $kec->logo)) || empty($logo)) {
            $gambar = '/storage/logo/1.png';
        } else {
            $gambar = '/storage/logo/' . $logo;
        }

        return view('transaksi.dokumen.bkm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bm($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit')->with('rek_kredit')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $logo = $kec->logo;
        if (!(Storage::exists('logo/' . $kec->logo)) || empty($logo)) {
            $gambar = '/storage/logo/1.png';
        } else {
            $gambar = '/storage/logo/' . $logo;
        }

        return view('transaksi.dokumen.bm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function bkmAngsuran($id)
    {
        $keuangan = new Keuangan;

        $kec = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
        $trx = Transaksi::where('idt', $id)->with('rek_debit', 'tr_idtp', 'tr_idtp.rek_kredit')->withSum('tr_idtp', 'jumlah')->first();
        $user = User::where('id', $trx->id_user)->first();

        $dir = User::where([
            ['level', '1'],
            ['jabatan', '1'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $sekr = User::where([
            ['level', '1'],
            ['jabatan', '3'],
            ['lokasi', Session::get('lokasi')]
        ])->first();

        $logo = $kec->logo;
        if (!(Storage::exists('logo/' . $kec->logo)) || empty($logo)) {
            $gambar = '/storage/logo/1.png';
        } else {
            $gambar = '/storage/logo/' . $logo;
        }

        return view('transaksi.jurnal_angsuran.dokumen.bkm')->with(compact('trx', 'kec', 'dir', 'sekr', 'gambar', 'keuangan'));
    }

    public function lpp($id)
    {
        $data['bulan'] = date('Y-m-t');
        $data['kec'] = Kecamatan::where('id', Session::get('lokasi'))->with('kabupaten')->first();
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

    public function regenerateReal($pinkel)
    {
        $keuangan = new Keuangan;
        if (!$pinkel) {
            return response()->json([
                'success' => false,
                'msg' => 'Error'
            ]);
        }

        $id_pinkel = $pinkel->id;
        $transaksi = Transaksi::select(
            'idtp',
            'tgl_transaksi'
        )->where([
            ['id_pinj', $pinkel->id],
            ['idtp', '!=', '0']
        ])->with([
            'tr_idtp'
        ])->groupBy('idtp', 'tgl_transaksi')->orderBy('tgl_transaksi', 'ASC')->orderBy('idtp', 'ASC')->get();

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

            $insert[$trx->idtp] = [
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

            if (count($trx->tr_idtp) > 0) {
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

                        $insert[$trx->idtp]['realisasi_pokok'] = $tr->jumlah;
                        $insert[$trx->idtp]['sum_pokok'] = $sum_pokok;
                        $insert[$trx->idtp]['saldo_pokok'] = $alokasi_pokok - $sum_pokok;
                        $insert[$trx->idtp]['tunggakan_pokok'] = $tunggakan_pokok;
                    }

                    if (in_array($tr->rekening_kredit, $rek_jasa)) {
                        $sum_jasa += intval($tr->jumlah);

                        $tunggakan_jasa = $ra->target_jasa - $sum_jasa;
                        if ($tunggakan_jasa <= 0) $tunggakan_jasa = 0;

                        $insert[$trx->idtp]['realisasi_jasa'] = $tr->jumlah;
                        $insert[$trx->idtp]['sum_jasa'] = $sum_jasa;
                        $insert[$trx->idtp]['saldo_jasa'] = $alokasi_jasa - $sum_jasa;
                        $insert[$trx->idtp]['tunggakan_jasa'] = $tunggakan_jasa;
                    }
                }
            }
        }
        RealAngsuran::insert($insert);

        return response()->json([
            'success' => true
        ]);
    }

    public function realisasi($id_pinkel)
    {
        $pinkel = PinjamanKelompok::where('id', $id_pinkel)->first();
        $this->regenerateReal($pinkel);

        return response()->json([
            'success' => true
        ]);
    }
}
