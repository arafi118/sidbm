<?php
ini_set('display_errors', '1');
session_start();
$koneksi = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'information_schema');
$trigger = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'dbm_laravel');

if (isset($_POST['copy'])) {
    $lokasi = htmlspecialchars($_POST['lokasi']);

    $kec = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM dbm_sidbm.kecamatan WHERE id='$lokasi'"));

    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.anggota_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.inventaris_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.kelompok_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.pinjaman_anggota_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.pinjaman_kelompok_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.real_angsuran_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.rekening_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.rencana_angsuran_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.transaksi_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.saldo_" . $lokasi);
    mysqli_query($koneksi, "DROP TABLE IF EXISTS dbm_laravel.ebudgeting_" . $lokasi);

    mysqli_query($koneksi, "DELETE FROM dbm_laravel.users WHERE lokasi='$lokasi'");
    mysqli_query($koneksi, "DELETE FROM dbm_laravel.kecamatan WHERE id='$lokasi'");
    mysqli_query($koneksi, "DELETE FROM dbm_laravel.desa WHERE kd_kec='$kec[kd_kec]'");

    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.anggota_$lokasi LIKE dbm_sidbm.anggota_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.inventaris_$lokasi LIKE dbm_sidbm.inventaris_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.kelompok_$lokasi LIKE dbm_sidbm.kelompok_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_anggota_$lokasi LIKE dbm_sidbm.pinjaman_anggota_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.pinjaman_kelompok_$lokasi LIKE dbm_sidbm.pinjaman_kelompok_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.real_angsuran_$lokasi LIKE dbm_sidbm.real_angsuran_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rekening_$lokasi LIKE dbm_sidbm.rekening_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.rencana_angsuran_$lokasi LIKE dbm_sidbm.rencana_angsuran_$lokasi");
    mysqli_query($koneksi, "CREATE TABLE dbm_laravel.transaksi_$lokasi LIKE dbm_sidbm.transaksi_$lokasi");

    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS dbm_laravel.saldo_$lokasi LIKE dbm_laravel.saldo");
    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS dbm_laravel.ebudgeting_$lokasi LIKE dbm_laravel.ebudgeting");

    mysqli_query($koneksi, "INSERT dbm_laravel.anggota_$lokasi SELECT * FROM dbm_sidbm.anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.inventaris_$lokasi SELECT * FROM dbm_sidbm.inventaris_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.kelompok_$lokasi SELECT * FROM dbm_sidbm.kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_anggota_$lokasi SELECT * FROM dbm_sidbm.pinjaman_anggota_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.pinjaman_kelompok_$lokasi SELECT * FROM dbm_sidbm.pinjaman_kelompok_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.real_angsuran_$lokasi SELECT * FROM dbm_sidbm.real_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rekening_$lokasi SELECT * FROM dbm_sidbm.rekening_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.rencana_angsuran_$lokasi SELECT * FROM dbm_sidbm.rencana_angsuran_$lokasi");
    mysqli_query($koneksi, "INSERT dbm_laravel.transaksi_$lokasi SELECT * FROM dbm_sidbm.transaksi_$lokasi");

    mysqli_query($koneksi, "INSERT INTO dbm_laravel.users SELECT * FROM dbm_sidbm.users WHERE lokasi='$lokasi'");
    mysqli_query($koneksi, "INSERT INTO dbm_laravel.kecamatan SELECT * FROM dbm_sidbm.kecamatan WHERE id='$lokasi'");
    mysqli_query($koneksi, "INSERT INTO dbm_laravel.desa SELECT * FROM dbm_sidbm.desa WHERE kd_kec='$kec[kd_kec]'");

    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.anggota_$lokasi CHANGE `usaha` `usaha` VARCHAR(50) NULL DEFAULT '0'");
    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.rekening_$lokasi ADD `parent_id` VARCHAR(50) NULL FIRST");
    mysqli_query($koneksi, "ALTER TABLE dbm_laravel.pinjaman_anggota_$lokasi CHANGE `kom_pokok` `kom_pokok` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `kom_jasa` `kom_jasa` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
    mysqli_query($koneksi, "UPDATE dbm_laravel.rekening_$lokasi SET parent_id=CONCAT(lev1, lev2, lev3) WHERE 1");

    $calk = '{"peraturan_desa":"","D":{"1":{"d":{"1":0,"2":0,"3":0}},"2":{"a":0,"b":0,"c":0}}}';
    mysqli_query($koneksi, "UPDATE dbm_laravel.kecamatan SET calk='$calk' WHERE id='$lokasi'");

    $query = mysqli_query($koneksi, "SELECT * FROM dbm_laravel.tanda_tangan_laporan WHERE lokasi='$lokasi'");
    if (mysqli_num_rows($query) <= 0) {
        $ttd_pelaporan = '"<table class=\"p0\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size: 11px;\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 33.3333%;\">&nbsp;<\/td>\r\n<td style=\"width: 33.3333%;\">&nbsp;<\/td>\r\n<td style=\"width: 33.3333%;\">&nbsp;<\/td>\r\n<\/tr>\r\n<\/tbody>\r\n<tbody>\r\n<tr>\r\n<td style=\"text-align: center;\">Diperiksa Oleh<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">Dilaporkan<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\"><strong>......................................<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>......................................<\/strong><\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\"><strong>Badan Pengawas<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>Bendahara<\/strong><\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">Disetujui Oleh<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>......................................<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>Direktur<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<\/tbody>\r\n<\/table>"';
        $ttd_spk = '"<table class=\"p0\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size: 12px;\"><tr><td style=\"width: 33.4186%;\">&nbsp;<\/td><td style=\"width: 33.4186%;\">&nbsp;<\/td><td style=\"width: 16.6545%;\">&nbsp;<\/td><td style=\"width: 16.5449%;\">&nbsp;<\/td><\/tr>\r\n<tbody>\r\n<tr>\r\n<td style=\"text-align: center;\">Pihak Pertama<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\" colspan=\"2\">Pihak Kedua<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\" colspan=\"4\">\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\"><strong>{kepala_lembaga}<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>{ketua}<\/strong><\/td>\r\n<td style=\"text-align: center;\"><strong>{sekretaris}<\/strong><\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\"><strong>Direktur<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>Ketua<\/strong><\/td>\r\n<td style=\"text-align: center;\"><strong>Sekretaris<\/strong><\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">Mengetahui<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">{sebutan_kades} {desa}<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\" colspan=\"4\">\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<p>&nbsp;<\/p>\r\n<\/td>\r\n<\/tr>\r\n<tr>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\"><strong>{kades}<\/strong><\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<td style=\"text-align: center;\">&nbsp;<\/td>\r\n<\/tr>\r\n<\/tbody>\r\n<\/table>"';

        mysqli_query($koneksi, "INSERT INTO dbm_laravel.tanda_tangan_laporan (`id`, `lokasi`, `tanda_tangan_pelaporan`, `tanda_tangan_spk`) VALUES (NULL, '$lokasi', '$ttd_pelaporan', '$ttd_spk')");
    }

    $create = "
    DELIMITER &&
    CREATE TRIGGER `create_saldo_debit_$lokasi` AFTER INSERT ON `transaksi_$lokasi`
        FOR EACH ROW BEGIN
            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0')), NEW.rekening_debit, YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0'), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi));

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0')), NEW.rekening_kredit, YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0'), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi));

        END; &&
    DELIMITER ;
    ";

    $update = "
    DELIMITER &&
    CREATE TRIGGER `update_saldo_debit_$lokasi` AFTER UPDATE ON `transaksi_$lokasi`
        FOR EACH ROW BEGIN

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0')), NEW.rekening_debit, YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0'), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi));

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0')), NEW.rekening_kredit, YEAR(NEW.tgl_transaksi), LPAD(MONTH(NEW.tgl_transaksi), 2, '0'), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(NEW.tgl_transaksi),'-01-01') AND LAST_DAY(NEW.tgl_transaksi));

        END; &&
    DELIMITER ;
    ";

    $delete = "
    DELIMITER &&
    CREATE TRIGGER `delete_saldo_debit_$lokasi` AFTER DELETE ON `transaksi_$lokasi`
        FOR EACH ROW BEGIN

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_debit, '.',''), YEAR(OLD.tgl_transaksi), LPAD(MONTH(OLD.tgl_transaksi), 2, '0')), OLD.rekening_debit, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_debit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi));

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_kredit, '.',''), YEAR(OLD.tgl_transaksi), LPAD(MONTH(OLD.tgl_transaksi), 2, '0')), OLD.rekening_kredit, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi))) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi)),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit AND tgl_transaksi BETWEEN CONCAT(YEAR(OLD.tgl_transaksi),'-01-01') AND LAST_DAY(OLD.tgl_transaksi));

        END; &&
    DELIMITER ;
    ";

    mysqli_query($trigger, $create);
    mysqli_query($trigger, $update);
    mysqli_query($trigger, $delete);

    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='1', jenis='1' WHERE kategori='1' AND jenis='3'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='1', jenis='3' WHERE kategori='5' AND jenis='2'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='2', jenis='3' WHERE kategori='6' AND jenis='2'");
    mysqli_query($koneksi, "UPDATE dbm_laravel.inventaris_$lokasi SET kategori='3', jenis='3' WHERE kategori='7' AND jenis='2'");

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