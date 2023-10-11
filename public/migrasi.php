<?php
ini_set('display_errors', '1');
$koneksi = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'information_schema');

if (isset($_POST['copy'])) {
    $lokasi = htmlspecialchars($_POST['lokasi']);

    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.anggota_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.inventaris_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.kelompok_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.pinjaman_anggota_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.pinjaman_kelompok_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.real_angsuran_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.rekening_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.rencana_angsuran_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.transaksi_" . $lokasi);

    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.anggota_$lokasi AS SELECT * FROM dbm_sidbm.anggota_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.inventaris_$lokasi AS SELECT * FROM dbm_sidbm.inventaris_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.kelompok_$lokasi AS SELECT * FROM dbm_sidbm.kelompok_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_anggota_$lokasi AS SELECT * FROM dbm_sidbm.pinjaman_anggota_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_kelompok_$lokasi AS SELECT * FROM dbm_sidbm.pinjaman_kelompok_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.real_angsuran_$lokasi AS SELECT * FROM dbm_sidbm.real_angsuran_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rekening_$lokasi AS SELECT * FROM dbm_sidbm.rekening_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rencana_angsuran_$lokasi AS SELECT * FROM dbm_sidbm.rencana_angsuran_$lokasi WHERE 1 = 0");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.transaksi_$lokasi AS SELECT * FROM dbm_sidbm.transaksi_$lokasi WHERE 1 = 0");

    mysqli_query($koneksi, "INSERT dbm_laravel.anggota_$lokasi SELECT * FROM dbm_sidbm.anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.inventaris_$lokasi SELECT * FROM dbm_sidbm.inventaris_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.kelompok_$lokasi SELECT * FROM dbm_sidbm.kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_anggota_$lokasi SELECT * FROM dbm_sidbm.pinjaman_anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_kelompok_$lokasi SELECT * FROM dbm_sidbm.pinjaman_kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.real_angsuran_$lokasi SELECT * FROM dbm_sidbm.real_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rekening_$lokasi SELECT * FROM dbm_sidbm.rekening_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rencana_angsuran_$lokasi SELECT * FROM dbm_sidbm.rencana_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.transaksi_$lokasi SELECT * FROM dbm_sidbm.transaksi_$lokasi");

    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.rekening_$lokasi ADD `parent_id` VARCHAR(50) NULL FIRST");
    mysqli_query($koneksi, "UPDATE dbm_laravel.rekening_$lokasi SET parent_id=CONCAT(lev1, lev2, lev3) WHERE 1");

    $query = mysqli_query($koneksi, "SELECT * FROM dbm_laravel.tanda_tangan_laporan WHERE lokasi='$lokasi'");
    if (mysqli_num_rows($query) <= 0) {
        mysqli_query($koneksi, "INSERT INTO dbm_laravel.tanda_tangan_laporan (`id`, `lokasi`, `tanda_tangan_pelaporan`, `tanda_tangan_spk`) VALUES (NULL, '$lokasi', '', '')");
    }

    echo "<script>location.href = '/migrasi.php';</script>";
} else {
?>

    <form action="" method="post">
        <input type="text" name="lokasi" id="lokasi">
        <button type="submit" name="copy" id="copy">Copy Tabel</button>
    </form>

<?php } ?>