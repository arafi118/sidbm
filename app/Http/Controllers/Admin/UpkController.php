<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Master\Kecamatan;
use App\Models\Master\KodeAkun;
use App\Models\Rekening as ModelsRekening;
use App\Models\Saldo;
use App\Models\Transaksi as ModelsTransaksi;
use App\Models\Upk\Anggota;
use App\Models\Upk\Kecamatan as UpkKecamatan;
use App\Models\Upk\Rekening;
use App\Models\Upk\Transaksi;
use App\Models\User;
use App\Utils\Keuangan;
use Config;
use DB;
use Illuminate\Http\Request;
use Session;

class UpkController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::join('kabupaten as kab', DB::raw('LEFT(kecamatan.kode, 5)'), '=', 'kab.kode')
            ->select('kecamatan.kode', 'kab.nama as nama_kab', 'kecamatan.nama as nama_kec')
            ->orderBy('kab.nama')
            ->orderBy('kecamatan.nama')
            ->get();

        $title = 'Migrasi UPK';
        return view('admin.migrasi_upk.index')->with(compact('title', 'kecamatan'));
    }

    public function Server($server)
    {
        $this->routeSQL($server);
        $kecamatan = UpkKecamatan::select(
            'kecamatan.id',
            'kecamatan.nama_kec',
            'kecamatan.kd_kab',
            'kabupaten.nama_kab',
        )->join('kabupaten', 'kabupaten.id', 'kecamatan.kd_kab')->orderBy('kabupaten.nama_kab', 'ASC')->orderBy('kecamatan.nama_kec', 'ASC')->get();

        return response()->json([
            'success' => true,
            'data' => $kecamatan
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'server',
            'lokasi',
            'lokasi_baru'
        ]);

        $server = $data['server'];
        $lokasi = explode('#', $data['lokasi'])[0];
        $kd_kec = explode('#', $data['lokasi_baru'])[0];

        $session = [];
        $session['server'] = $server;
        $session['lokasi'] = $lokasi;
        $session['kd_kec'] = $kd_kec;

        $this->routeSQL($server);
        $kec = UpkKecamatan::where('id', $lokasi)->first();

        Session::put('lokasi', $kec->id);
        Session::put('id_kab', $kec->kd_kab);
        $pinjaman_anggota = Anggota::where('lokasi', $lokasi)->whereHas('pinj', function ($query) {
            $query->where('jenis_pinjaman', 'K');
        })->count();
        $pinjaman_individu = Anggota::where('lokasi', $lokasi)->whereHas('pinj', function ($query) {
            $query->where('jenis_pinjaman', 'I');
        })->count();

        $translate = KodeAkun::all();
        $session['kd_kab'] = $kec->kd_kab;
        $session['pinjaman_anggota'] = $pinjaman_anggota;
        $session['pinjaman_individu'] = $pinjaman_individu;
        $session['translate'] = $translate;

        DB::statement('DROP TABLE IF EXISTS anggota_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS ebudgeting_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS inventaris_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS kelompok_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS pinjaman_anggota_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS pinjaman_kelompok_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS real_angsuran_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS rekening_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS rencana_angsuran_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS saldo_' . $lokasi);
        DB::statement('DROP TABLE IF EXISTS transaksi_' . $lokasi);

        Desa::where('kd_kec', $kd_kec)->delete();
        User::where('lokasi', $lokasi)->delete();

        Session::put('lokasi_' . $lokasi, $session);
        return redirect('/master/migrasi_upk/' . $lokasi . '/rekening');
    }

    public function Rekening($id)
    {
        $key = 'lokasi_' . $id;
        $session = Session::get($key);
        $translate = $session['translate'];

        $this->routeSQL($session['server']);
        $rekening = Rekening::all();

        $struktur = DB::connection('upk')->select('SHOW COLUMNS FROM rekening_' . $session['lokasi']);
        $kolom_tb = collect($struktur)->filter(function ($column) {
            return stripos($column->Field, 'tb') !== false;
        });

        $kode_rekening = [];
        $insert = [];
        foreach ($translate as $trs) {
            $kd_rekening = $trs->kode_akun;
            $kode_akun = $trs->translate;

            if (!array_key_exists($kode_akun, $insert)) {
                foreach ($kolom_tb as $tb) {
                    $tahun = str_replace('tb', '', $tb->Field);
                    $insert[$kode_akun][$tahun] = [
                        'debit' => 0,
                        'kredit' => 0
                    ];

                    $session['tahun'][$tahun] = $tahun;
                }
            }

            foreach ($rekening as $rek) {
                if (Keuangan::startWith($rek->kd_rekening, $kd_rekening)) {
                    if ($kd_rekening == '511' && $rek->kd_rekening == '511.13') {
                        continue;
                    }

                    $kode_rekening[$rek->kd_rekening] = $kode_akun;
                    foreach ($kolom_tb as $tb) {
                        $field = $tb->Field;
                        $tahun = str_replace('tb', '', $field);

                        if ($rek->jenis_mutasi == 'debit') {
                            $insert[$kode_akun][$tahun]['debit'] += $rek->$field;
                        }

                        if ($rek->jenis_mutasi == 'kredit') {
                            $insert[$kode_akun][$tahun]['kredit'] += $rek->$field;
                        }
                    }
                }
            }
        }

        $session['rekening'] = $insert;
        $session['translate'] = $kode_rekening;

        Session::put('lokasi_' . $session['lokasi'], $session);
        return redirect('/master/migrasi_upk/' . $session['lokasi'] . '/rekening/insert');
    }

    public function InsertRekening($id)
    {
        $session = Session::get('lokasi_' . $id);

        $database = env('DB_DATABASE', 'forge');
        $db_master = env('DB_MASTER_DATABASE', 'forge');

        DB::statement("CREATE TABLE IF NOT EXISTS $database.rekening_$session[lokasi] LIKE $db_master.rekening");
        DB::statement("CREATE TABLE IF NOT EXISTS $database.saldo_$session[lokasi] LIKE $db_master.saldo");

        Session::put('lokasi', $session['lokasi']);
        if (ModelsRekening::count() <= 0) {
            DB::statement("INSERT INTO $database.rekening_$session[lokasi] SELECT * FROM $db_master.rekening");
        }

        $saldo = [];
        $rekening = ModelsRekening::all();
        foreach ($rekening as $rek) {
            foreach ($session['tahun'] as $tahun) {

                $debit = 0;
                $kredit = 0;
                if (array_key_exists($rek->kode_akun, $session['rekening'])) {
                    $debit = $session['rekening'][$rek->kode_akun][$tahun]['debit'];
                    $kredit = $session['rekening'][$rek->kode_akun][$tahun]['kredit'];
                }

                $id = str_replace('.', '', $rek->kode_akun) . $tahun . str_pad(0, 2, "0", STR_PAD_LEFT);
                $saldo[] = [
                    'id' => $id,
                    'kode_akun' => $rek->kode_akun,
                    'tahun' => $tahun,
                    'bulan' => 0,
                    'debit' => $debit,
                    'kredit' => $kredit
                ];
            }
        }

        DB::statement("TRUNCATE TABLE $database.saldo_$session[lokasi]");

        $chunks = array_chunk($saldo, 500);
        foreach ($chunks as $chunk) {
            Saldo::insert($chunk);
        }

        unset($session['rekening']);
        Session::put('lokasi_' . $session['lokasi'], $session);
        return redirect('/master/migrasi_upk/' . $session['lokasi'] . '/transaksi');
    }

    public function Transaksi($id)
    {
        $session = Session::get('lokasi_' . $id);
        $translate = $session['translate'];

        $database = env('DB_DATABASE', 'forge');
        $db_master = env('DB_MASTER_DATABASE', 'forge');

        DB::statement("CREATE TABLE IF NOT EXISTS $database.transaksi_$session[lokasi] LIKE $db_master.transaksi");

        $insert = [];
        $data_idt = [];
        $this->routeSQL($session['server']);
        $transaksi = Transaksi::paginate(200);
        foreach ($transaksi as $trx) {

            $rekening_debit = $trx->rekening_debit;
            if (array_key_exists($rekening_debit, $translate)) {
                $rekening_debit = $translate[$rekening_debit];
            }

            $rekening_kredit = $trx->rekening_kredit;
            if (array_key_exists($rekening_kredit, $translate)) {
                $rekening_kredit = $translate[$rekening_kredit];
            }

            $insert[] = [
                'idt' => $trx->idt,
                'tgl_transaksi' => $trx->tgl_transaksi,
                'rekening_debit' => $rekening_debit,
                'rekening_kredit' => $rekening_kredit,
                'idtp' => $trx->idtp,
                'id_pinj' => $trx->id_pinj,
                'id_pinj_i' => $trx->id_pinj_i,
                'keterangan_transaksi' => $trx->keterangan_transaksi,
                'relasi' => '-',
                'jumlah' => $trx->jumlah,
                'urutan' => $trx->urutan,
                'id_user' => $trx->id_user,
            ];

            $data_idt[] = $trx->idt;
        }

        if ($transaksi->onFirstPage()) {
            DB::statement("TRUNCATE TABLE $database.transaksi_$session[lokasi]");
        } else {
            Transaksi::whereIn('idt', $data_idt)->delete();
        }

        ModelsTransaksi::insert($insert);

        $is_next = $transaksi->hasMorePages();
        if ($is_next) {
            $link = $transaksi->nextPageUrl();
            $total_row = $transaksi->total();
            $per_page = $transaksi->perPage();
            $cur_page = $transaksi->currentPage();

            return view('admin.migrasi_upk.partials.transaksi')->with(compact('link', 'total_row', 'per_page', 'cur_page'));
        }

        Session::put('lokasi_' . $session['lokasi'], $session);
        return redirect('/master/migrasi_upk/' . $session['lokasi'] . '/desa');
    }

    public function Desa($id)
    {
        dd('');
    }

    protected function routeSQL($server)
    {
        $database = env('DB_UPK_NET_DATABASE', 'forge');
        if ($server == 'com') {
            $database = env('DB_UPK_COM_DATABASE', $database);
        }

        if ($server == 'new') {
            $database = env('DB_UPK_NEW_DATABASE', $database);
        }

        Config::set('database.connections.upk', [
            'driver' => 'mysql',
            'host' => env('DB_UPK_HOST', '127.0.0.1'),
            'port' => env('DB_UPK_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_UPK_USERNAME', 'forge'),
            'password' => env('DB_UPK_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
    }
}
