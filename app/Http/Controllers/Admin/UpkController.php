<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master\Kecamatan;
use App\Models\Upk\Kecamatan as UpkKecamatan;
use Config;
use DB;
use Illuminate\Http\Request;

class UpkController extends Controller
{
    public function index()
    {
        $kec = Kecamatan::join('kabupaten as kab', DB::raw('LEFT(kecamatan.kode, 5)'), '=', 'kab.kode')
            ->select('kecamatan.kode', 'kab.nama as nama_kab', 'kecamatan.nama as nama_kec')
            ->orderBy('kecamatan.nama')
            ->get();

        $title = 'Migrasi UPK';
        return view('admin.migrasi_upk.index')->with(compact('title'));
    }

    public function Server($server)
    {
        $this->routeSQL($server);
        $kecamatan = UpkKecamatan::with('kab')->orderBy('id', 'ASC')->get();

        return response()->json([
            'success' => true,
            'data' => $kecamatan
        ]);
    }

    protected function routeSQL($server)
    {
        $database = env('DB_UPK_DATABASE', 'forge');
        if ($server == 'com') {
            $database = env('DB_UPK_COM_DATABASE', $database);
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
