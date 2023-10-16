<?php
ini_set('display_errors', '1');
session_start();
$koneksi = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'information_schema');
$trigger = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'dbm_laravel');

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

    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.anggota_$lokasi LIKE dbm_sidbm.anggota_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.inventaris_$lokasi LIKE dbm_sidbm.inventaris_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.kelompok_$lokasi LIKE dbm_sidbm.kelompok_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_anggota_$lokasi LIKE dbm_sidbm.pinjaman_anggota_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_kelompok_$lokasi LIKE dbm_sidbm.pinjaman_kelompok_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.real_angsuran_$lokasi LIKE dbm_sidbm.real_angsuran_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rekening_$lokasi LIKE dbm_sidbm.rekening_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rencana_angsuran_$lokasi LIKE dbm_sidbm.rencana_angsuran_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.transaksi_$lokasi LIKE dbm_sidbm.transaksi_$lokasi");

    mysqli_query($koneksi, "INSERT dbm_laravel.anggota_$lokasi SELECT * FROM dbm_sidbm.anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.inventaris_$lokasi SELECT * FROM dbm_sidbm.inventaris_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.kelompok_$lokasi SELECT * FROM dbm_sidbm.kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_anggota_$lokasi SELECT * FROM dbm_sidbm.pinjaman_anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_kelompok_$lokasi SELECT * FROM dbm_sidbm.pinjaman_kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.real_angsuran_$lokasi SELECT * FROM dbm_sidbm.real_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rekening_$lokasi SELECT * FROM dbm_sidbm.rekening_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rencana_angsuran_$lokasi SELECT * FROM dbm_sidbm.rencana_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.transaksi_$lokasi SELECT * FROM dbm_sidbm.transaksi_$lokasi");

    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.anggota_$lokasi CHANGE `usaha` `usaha` VARCHAR(50) NULL DEFAULT '0'");
    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.rekening_$lokasi ADD `parent_id` VARCHAR(50) NULL FIRST");
    mysqli_query($koneksi, "UPDATE dbm_laravel.rekening_$lokasi SET parent_id=CONCAT(lev1, lev2, lev3) WHERE 1");

    $query = mysqli_query($koneksi, "SELECT * FROM dbm_laravel.tanda_tangan_laporan WHERE lokasi='$lokasi'");
    if (mysqli_num_rows($query) <= 0) {
        mysqli_query($koneksi, "INSERT INTO dbm_laravel.tanda_tangan_laporan (`id`, `lokasi`, `tanda_tangan_pelaporan`, `tanda_tangan_spk`) VALUES (NULL, '$lokasi', '', '')");
    }

    $trigger_create = "
        CREATE TRIGGER `create_saldo_debit_$lokasi` AFTER INSERT ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit);

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit);
    
        END
    ";

    $trigger_update = "
        CREATE TRIGGER `update_saldo_debit_$lokasi` AFTER UPDATE ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit);

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit);

        END
    ";

    $trigger_delete = "
        CREATE TRIGGER `delete_saldo_debit_$lokasi` AFTER DELETE ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_debit, '.',''), 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_debit, 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_debit);

            INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_kredit, '.',''), 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_kredit, 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit);

        END
    ";

    // mysqli_query($trigger, $trigger_create);
    // mysqli_query($trigger, $trigger_update);
    // mysqli_query($trigger, $trigger_delete);

    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='1', jenis='1' WHERE kategori='1' AND jenis='3'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='1', jenis='3' WHERE kategori='5' AND jenis='5'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='2', jenis='3' WHERE kategori='6' AND jenis='5'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='3', jenis='3' WHERE kategori='7' AND jenis='5'");

    $_SESSION['success'] = "Copy Tabel Lokasi <b>$lokasi</b> Berhasil.";
    echo "<script>location.href = '/migrasi.php';</script>";
} else {
?>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<div>$_SESSION[success]</div>";

        unset($_SESSION['success']);
    }
    ?>

    <form action="" method="post">
        <input type="text" name="lokasi" id="lokasi">
        <button type="submit" name="copy" id="copy">Copy Tabel</button>
    </form>

<?php } ?>