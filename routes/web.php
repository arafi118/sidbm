<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\PinjamanAnggotaController;
use App\Http\Controllers\PinjamanKelompokController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->middleware('guest')->name('/');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/piutang_jasa', [DashboardController::class, 'piutang'])->middleware('auth');

Route::post('/dashboard/jatuh_tempo', [DashboardController::class, 'jatuhTempo'])->middleware('auth');
Route::post('/dashboard/nunggak', [DashboardController::class, 'nunggak'])->middleware('auth');
Route::post('/dashboard/line', [DashboardController::class, 'lineChart'])->middleware('auth');

Route::get('/pengaturan/sop', [SopController::class, 'index'])->middleware('auth');
Route::get('/pengaturan/ttd_pelaporan', [SopController::class, 'ttdPelaporan'])->middleware('auth');
Route::get('/pengaturan/ttd_spk', [SopController::class, 'ttdSpk'])->middleware('auth');

Route::post('/pengaturan/sop/simpanttdpelaporan', [SopController::class, 'simpanTtdPelaporan'])->middleware('auth');

Route::get('/database/kelompok/register_kelompok', [KelompokController::class, 'register'])->middleware('auth');
Route::get('/database/kelompok/generatekode', [KelompokController::class, 'generateKode'])->middleware('auth');

Route::get('/database/penduduk/register_penduduk', [AnggotaController::class, 'register'])->middleware('auth');
Route::get('/database/penduduk/cari_nik', [AnggotaController::class, 'cariNik'])->middleware('auth');

Route::get('/database/kelompok/detail_kelompok/{id}', [KelompokController::class, 'detailKelompok'])->middleware('auth');
Route::resource('/database/desa', DesaController::class)->middleware('auth');
Route::resource('/database/kelompok', KelompokController::class)->middleware('auth');
Route::resource('/database/penduduk', AnggotaController::class)->middleware('auth');

Route::get('/register_proposal', [PinjamanKelompokController::class, 'create'])->middleware('auth');
Route::get('/register_proposal/{id_kel}', [PinjamanKelompokController::class, 'register'])->middleware('auth');
Route::get('/daftar_kelompok', [PinjamanKelompokController::class, 'DaftarKelompok'])->middleware('auth');

Route::get('/detail/{perguliran}', [PinjamanKelompokController::class, 'detail'])->middleware('auth');
Route::get('/perguliran/proposal', [PinjamanKelompokController::class, 'proposal'])->middleware('auth');
Route::get('/perguliran/verified', [PinjamanKelompokController::class, 'verified'])->middleware('auth');
Route::get('/perguliran/waiting', [PinjamanKelompokController::class, 'waiting'])->middleware('auth');
Route::get('/perguliran/aktif', [PinjamanKelompokController::class, 'aktif'])->middleware('auth');
Route::get('/perguliran/lunas', [PinjamanKelompokController::class, 'lunas'])->middleware('auth');
Route::get('/perguliran/generate/{id_pinj}', [PinjamanKelompokController::class, 'generate'])->middleware('auth');
Route::get('/lunas/{perguliran}', [PinjamanKelompokController::class, 'pelunasan'])->middleware('auth');
Route::get('/cetak_keterangan_lunas/{perguliran}', [PinjamanKelompokController::class, 'keterangan'])->middleware('auth');

Route::get('/perguliran/cari_kelompok', [PinjamanKelompokController::class, 'cariKelompok'])->middleware('auth');
Route::post('/perguliran/simpan_data/{id}', [PinjamanKelompokController::class, 'simpan'])->middleware('auth');
Route::post('/perguliran/rescedule', [PinjamanKelompokController::class, 'rescedule'])->middleware('auth');
Route::post('/perguliran/hapus', [PinjamanKelompokController::class, 'hapus'])->middleware('auth');
Route::resource('/perguliran', PinjamanKelompokController::class)->middleware('auth');

Route::get('/perguliran/dokumen/kartu_angsuran/{id}', [PinjamanKelompokController::class, 'kartuAngsuran'])->middleware('auth');
Route::get('/perguliran/dokumen/kartu_angsuran/{id}/{idtp}', [PinjamanKelompokController::class, 'cetakPadaKartu'])->middleware('auth');
Route::post('/perguliran/dokumen', [PinjamanKelompokController::class, 'dokumen'])->middleware('auth');

Route::post('/perguliran/kembali_proposal/{id}', [PinjamanKelompokController::class, 'kembaliProposal'])->middleware('auth');

Route::get('/pinjaman_anggota/register/{id_pinkel}', [PinjamanAnggotaController::class, 'create'])->middleware('auth');
Route::get('/pinjaman_anggota/cari_pemanfaat', [PinjamanAnggotaController::class, 'cariPemanfaat'])->middleware('auth');
Route::get('/hapus_pemanfaat/{id}', [PinjamanAnggotaController::class, 'hapus'])->middleware('auth');

Route::resource('/pinjaman_anggota', PinjamanAnggotaController::class)->middleware('auth');

Route::get('/transaksi/jurnal_umum', [TransaksiController::class, 'jurnalUmum'])->middleware('auth');
Route::get('/transaksi/jurnal_angsuran', [TransaksiController::class, 'jurnalAngsuran'])->middleware('auth');
Route::get('/trasaksi/saldo/{kode_akun}', [TransaksiController::class, 'saldo'])->middleware('auth');

Route::get('/transaksi/ambil_rekening/{id}', [TransaksiController::class, 'rekening'])->middleware('auth');
Route::get('/transaksi/form_nominal/', [TransaksiController::class, 'form'])->middleware('auth');
Route::get('/transaksi/form_angsuran/{id_pinkel}', [TransaksiController::class, 'formAngsuran'])->middleware('auth');

Route::get('/transaksi/data/{idt}', [TransaksiController::class, 'data'])->middleware('auth');
Route::post('/transaksi/reversal', [TransaksiController::class, 'reversal'])->middleware('auth');
Route::post('/transaksi/hapus', [TransaksiController::class, 'hapus'])->middleware('auth');

Route::get('/transaksi/angsuran/lpp/{id}', [TransaksiController::class, 'lpp'])->middleware('auth');
Route::get('/transaksi/angsuran/detail_angsuran/{id}', [TransaksiController::class, 'detailAngsuran'])->middleware('auth');
Route::get('/transaksi/angsuran/struk/{id}', [TransaksiController::class, 'struk'])->middleware('auth');
Route::get('/transaksi/angsuran/struk_matrix/{id}', [TransaksiController::class, 'strukMatrix'])->middleware('auth');
Route::get('/transaksi/detail_transaksi/', [TransaksiController::class, 'detailTransaksi'])->middleware('auth');
Route::post('/transaksi/angsuran', [TransaksiController::class, 'angsuran'])->middleware('auth');
Route::get('/transaksi/generate_real/{id_pinkel}', [TransaksiController::class, 'generateReal'])->middleware('auth');
Route::get('/transaksi/regenerate_real/{id_pinkel}', [TransaksiController::class, 'regenerateReal'])->middleware('auth');

Route::get('/transaksi/dokumen/kuitansi/{id}', [TransaksiController::class, 'kuitansi'])->middleware('auth');
Route::get('/transaksi/dokumen/bkk/{id}', [TransaksiController::class, 'bkk'])->middleware('auth');
Route::get('/transaksi/dokumen/bkm/{id}', [TransaksiController::class, 'bkm'])->middleware('auth');
Route::get('/transaksi/dokumen/bm/{id}', [TransaksiController::class, 'bm'])->middleware('auth');

Route::get('/transaksi/dokumen/bkm_angsuran/{id}', [TransaksiController::class, 'bkmAngsuran'])->middleware('auth');
Route::get('/transaksi/dokumen/bkk_angsuran/{id}', [TransaksiController::class, 'bkkAngsuran'])->middleware('auth');

Route::resource('/transaksi', TransaksiController::class)->middleware('auth');

Route::get('/pelaporan', [PelaporanController::class, 'index'])->middleware('auth');
Route::get('/pelaporan/sub_laporan/{file}', [PelaporanController::class, 'subLaporan'])->middleware('auth');
Route::post('/pelaporan/preview', [PelaporanController::class, 'preview'])->middleware('auth');

Route::get('/sync/{lokasi}', [DashboardController::class, 'sync'])->middleware('auth');
Route::get('/link', function () {
    $target = '/home/dbm/public_html/apps/storage/app/public';
    $shortcut = '/home/dbm/public_html/apps/public/storage';
    symlink($target, $shortcut);
});

Route::get('/generate/{id}', function ($id) {
    $pinjaman = new PinjamanKelompokController;
    $transaksi = new TransaksiController;

    $pinjaman->generate($id);
    $transaksi->regenerateReal($id);

    return response()->json([
        'msg' => 'Generate Real dan Rencana Berhasil'
    ]);
});
