DELIMITER $$
    CREATE TRIGGER `create_saldo_debit` AFTER INSERT ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_debit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_debit);

    END$$
DELIMITER ;

DELIMITER $$
    CREATE TRIGGER `create_saldo_kredit` AFTER INSERT ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_kredit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_kredit);

    END$$
DELIMITER ;

DELIMITER $$
    CREATE TRIGGER `delete_saldo_debit` AFTER DELETE ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(OLD.rekening_debit, '.',''), 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_debit, 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=OLD.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=OLD.rekening_debit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=OLD.rekening_debit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=OLD.rekening_debit);

    END$$
DELIMITER ;

DELIMITER $$
    CREATE TRIGGER `delete_saldo_kredit` AFTER DELETE ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(OLD.rekening_kredit, '.',''), 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi)), OLD.rekening_kredit, 1, YEAR(OLD.tgl_transaksi), MONTH(OLD.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=OLD.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=OLD.rekening_kredit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=OLD.rekening_kredit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=OLD.rekening_kredit);

    END$$
DELIMITER ;

DELIMITER $$
    CREATE TRIGGER `update_saldo_debit` AFTER UPDATE ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(NEW.rekening_debit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_debit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_debit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_debit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_debit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_debit);

    END$$
DELIMITER ;

DELIMITER $$
    CREATE TRIGGER `update_saldo_kredit` AFTER UPDATE ON `transaksi_1`
    FOR EACH ROW BEGIN

        INSERT INTO saldo (`id`, `kode_akun`, `lokasi`, `tahun`, `bulan`, `debit`, `kredit`)
        VALUES (CONCAT(REPLACE(NEW.rekening_kredit, '.',''), 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi)), NEW.rekening_kredit, 1, YEAR(NEW.tgl_transaksi), MONTH(NEW.tgl_transaksi), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_kredit), (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_kredit)) ON DUPLICATE KEY UPDATE 
        debit= (SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_debit=NEW.rekening_kredit),
        kredit=(SELECT SUM(jumlah) FROM transaksi_1 WHERE rekening_kredit=NEW.rekening_kredit);

    END$$
DELIMITER ;