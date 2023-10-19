<?php
$koneksi = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'information_schema');
$trigger = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'dbm_laravel');

$query = mysqli_query($koneksi, "SELECT * FROM `TABLES` WHERE TABLE_SCHEMA='dbm_laravel' AND TABLE_NAME LIKE 'kelompok_%'");
foreach ($query as $tb) {
    $table = explode('_', $tb);

    $tabel = $table[0];
    $lokasi = $table[1];

    $trigger_create = "
        CREATE TRIGGER `create_saldo_debit_$lokasi` AFTER INSERT ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit);

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit);
    
        END
    ";

    $trigger_update = "
        CREATE TRIGGER `update_saldo_debit_$lokasi` AFTER UPDATE ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_debit);

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=NEW.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=NEW.rekening_kredit);

        END
    ";

    $trigger_delete = "
        CREATE TRIGGER `delete_saldo_debit_$lokasi` AFTER DELETE ON `transaksi_$lokasi` FOR EACH ROW BEGIN

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_debit, '.',''), YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_debit, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_debit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_debit);

            INSERT INTO saldo_$lokasi (`id`, `kode_akun`, `tahun`, `bulan`, `debit`, `kredit`)
                VALUES (CONCAT(REPLACE(OLD.rekening_kredit, '.',''), YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_kredit, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit)) ON DUPLICATE KEY UPDATE 
                debit= (SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_debit=OLD.rekening_kredit),
                kredit=(SELECT SUM(jumlah) FROM transaksi_$lokasi WHERE rekening_kredit=OLD.rekening_kredit);

        END
    ";

    mysqli_query($trigger, $trigger_create);
    mysqli_query($trigger, $trigger_update);
    mysqli_query($trigger, $trigger_delete);
}
