<?php
ini_set('display_errors', '1');
$koneksi = mysqli_connect('localhost', 'dbm_sidbm', 'dbm_sidbm', 'dbm_laravel');

if (isset($_GET['lokasi']) && isset($_GET['where'])) {
    $lokasi = $_GET['lokasi'];
    $limit = $_GET['where'];
    $datetime = date('Y-m-d H:i:s');

    // mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS rencana_angsuran_$lokasi LIKE rencana_angsuran_1");
    // mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS real_angsuran_$lokasi LIKE real_angsuran_1");

    $total = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pinjaman_kelompok_$lokasi WHERE (status='A' OR status='W' OR status='L' OR status='H' OR status='R') 
                        AND ($limit) ORDER BY id ASC"));
    echo "Total Pinjaman Kelompok Lokasi $lokasi = " . $total . "<br><br>";

    if (isset($_GET['limit']) && isset($_GET['start'])) {
        $start = $_GET['start'] + $_GET['limit'];
        $per_page = 25;
    } else {
        $start = 0;
        $per_page = 25;
    }
?>
    <form action="" method="get">
        <input type="hidden" name="lokasi" id="lokasi" value="<?php echo $lokasi; ?>">
        <input type="hidden" name="where" id="where" value="<?php echo $limit; ?>">
        <input type="text" name="start" id="start" value="<?php echo $start; ?>" autofocus>
        <input type="text" name="limit" id="limit" value="<?php echo $per_page; ?>" readonly>
        <button type="submit" name="migrate" id="migrate">Run</button>
    </form>

    <?php
    function startWith($string, $startString)
    {
        $string = (string) $string;
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    function bulatkan($angka)
    {
        $angka = round($angka);

        global $koneksi;
        $lokasi = $_GET['lokasi'];
        $kec = mysqli_fetch_array(mysqli_query($koneksi, "select * from kecamatan WHERE id='$lokasi'"));
        $pembulatan = (string) $kec['pembulatan'];

        $sistem = 'auto';
        if (startWith($pembulatan, '+')) {
            $sistem = 'keatas';
            $pembulatan = intval($pembulatan);
        }

        if (startWith($pembulatan, '-')) {
            $sistem = 'kebawah';
            $pembulatan = intval($pembulatan * -1);
        }

        $ratusan = substr($angka, -3);
        $nilai_tengah = $pembulatan / 2;

        if ($sistem == 'keatas') {
            $akhir = $angka + ($pembulatan - $ratusan);
        }

        if ($sistem == 'kebawah') {
            $akhir = $angka - $ratusan;
        }

        if ($sistem == 'auto') {
            if ($ratusan < $nilai_tengah) {
                $akhir = $angka - $ratusan;
            } else {
                $akhir = $angka + ($pembulatan - $ratusan);
            }
        }

        return $akhir;
    }
    // Ambil Data Pinjaman Kelompok
    $pinjaman_kelompok = mysqli_query($koneksi, "SELECT * FROM pinjaman_kelompok_$lokasi WHERE (status='A' OR status='W' OR status='L' OR status='H' OR status='R') 
                        AND ($limit) ORDER BY id ASC LIMIT $start, $per_page");

    $kec = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kecamatan WHERE id='$lokasi'"));
    while ($pk = mysqli_fetch_array($pinjaman_kelompok)) {
        $kel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelompok_$lokasi WHERE id='$pk[id_kel]'"));
        $desa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM desa WHERE kd_desa='$kel[desa]'"));
        $del_re = mysqli_query($koneksi, "DELETE FROM real_angsuran_$lokasi WHERE loan_id=$pk[id]");
        $del_ra = mysqli_query($koneksi, "DELETE FROM rencana_angsuran_$lokasi WHERE loan_id=$pk[id]");

        if ($pk['tgl_cair'] == "0000-00-00") {
            $tgl_cair = $pk['tgl_tunggu'];
        } else {
            $tgl_cair = $pk['tgl_cair'];
        }

        $tgl_angsur = $tgl_cair;
        $tanggal_cair = date('d', strtotime($tgl_cair));

        if ($kec['reset_inventaris'] > 0) {
            $batas_tgl_angsuran = $kec['reset_inventaris'];
            if ($tanggal_cair >= $batas_tgl_angsuran) {
                $tgl_cair = date('d-m-Y', strtotime('+1 month', strtotime($tgl_cair)));
            }
        }

        if ($desa['jadwal_angsuran_desa'] > 0) {
            $angsuran_desa = $desa['jadwal_angsuran_desa'];
            if ($angsuran_desa > 0) {
                $tgl_pinjaman = date('Y-m', strtotime($tgl_cair));
                $tgl_cair = $tgl_pinjaman . '-' . $angsuran_desa;
            }
        }

        $tgllalu = $tgl_cair;

        $jenis_pp = $pk['jenis_pp'];
        if ($jenis_pp == '1') {
            $poko_kredit = '1.1.03.01';
            $jasa_kredit = '4.1.01.01';
            $dend_kredit = '4.1.01.04';
        } elseif ($jenis_pp == '2') {
            $poko_kredit = '1.1.03.02';
            $jasa_kredit = '4.1.01.02';
            $dend_kredit = '4.1.01.05';
        } else {
            $poko_kredit = '1.1.03.03';
            $jasa_kredit = '4.1.01.03';
            $dend_kredit = '4.1.01.06';
        }

        // Ambil Sistem Angsuran Pinjaman (Pokok dan Jasa)
        $sipokok = mysqli_fetch_array(mysqli_query($koneksi, "select * from sistem_angsuran WHERE id='$pk[sistem_angsuran]'"));
        $sijasa = mysqli_fetch_array(mysqli_query($koneksi, "select * from sistem_angsuran WHERE id='$pk[sa_jasa]'"));

        $alokasi = $pk['alokasi'];
        $jangka = $pk['jangka'];
        $pros_jasa = $pk['pros_jasa'] / $jangka;

        $tarp = 0;
        $tarj = 0;

        // Cek Rencana Angsuran Ke-0
        if (mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM rencana_angsuran_$lokasi WHERE loan_id='$pk[id]' AND angsuran_ke='0'")) <= 0) {
            // Insert Rencana Angsuran Ke-0
            $input_ra = mysqli_query($koneksi, "INSERT INTO `rencana_angsuran_$lokasi`
            (`id`, `loan_id`, `angsuran_ke`, `jatuh_tempo`, `wajib_pokok`, `wajib_jasa`, `target_pokok`, `target_jasa`, `lu`,
            `id_user`) VALUES (null,'$pk[id]','0','$tgl_cair','0','0','0','0','$datetime','1')");
        }

        // Loop Jangka Untuk Membuat Rencana Angsuran
        for ($ke = 1; $ke <= $pk['jangka']; $ke++) {

            $tgl = explode('-', $tgl_cair);
            $tanggal = $tgl[2];
            $bulan = $tgl[1];
            $tahun = $tgl[0];

            $b = $bulan + $ke;
            $bagi_b = floor($b / 12);

            if ($b > 12) {
                if ($b % 12 == 0) {
                    $bl = $b - (12 * $bagi_b) + 12;
                } else {
                    $bl = $b - (12 * $bagi_b);
                    $th = $tahun + $bagi_b;
                }
            } else {
                $bl = $b;
                $th = $tahun;
            }

            if ($bl < 10) {
                $bln = "0$bl";
            } else {
                $bln = $bl;
            }

            // Validasi tanggal yang dihasilkan
            $lastDayOfMonth = date('t', strtotime("$th-$bln-01")); // Mendapatkan jumlah hari dalam bulan yang dihasilkan
            if ($tanggal > $lastDayOfMonth) {
                $tanggal = $lastDayOfMonth; // Menggunakan tanggal terakhir yang valid dalam bulan tersebut
            }

            $tglang = "$th-$bln-$tanggal";

            $sapokok = intval($sipokok['sistem']);
            $tempokok = @($jangka / $sapokok);
            $satuanwapok = bulatkan(@($alokasi / $tempokok));
            $wapok = 0;
            if ($ke % $sapokok == 0) {
                $wapok = $satuanwapok;
            }

            if ($ke == $jangka) {
                $sump = $wapok * ($ke / $sapokok - 1);
                $wapok = $alokasi - $sump;
            }

            if ($pk['sistem_angsuran'] == 20) {
                $satuanwapok = bulatkan(@($alokasi / ($jangka - 12)));
                if ($ke > 12) {
                    $wapok = $satuanwapok;
                } else {
                    $wapok = 0;
                }
                if ($ke == $jangka) {
                    $sump = $wapok * ($ke / $sapokok - 13);
                    $wapok = $alokasi - $sump;
                }
            }

            $sajasa = $sijasa['sistem'];
            $temjasa = @($jangka / $sajasa);
            $satuanwajas = bulatkan($alokasi * ($pros_jasa / 100) * $sajasa);
            if ($ke % $sajasa == 0) {
                $wajas = $satuanwajas;
            } else {
                $wajas = 0;
            }

            if ($ke == $jangka) {
                $sumj = $wajas * ($ke / $sajasa - 1);
                $wajas = bulatkan($alokasi * ($pk['pros_jasa'] / 100)) - $sumj;
            }

            $tarp = $tarp + $wapok;
            if ($ke == $jangka) {
                $tarp = $alokasi;
            }

            $tarj = $tarj + $wajas;
            $ra = mysqli_query($koneksi, "SELECT * FROM rencana_angsuran_$lokasi WHERE loan_id='$pk[id]' AND jatuh_tempo='$tglang'");

            // Cek apakah data yang akan di input sudah ada didalam tabel rencana_angsuran_$lokasi
            if (mysqli_num_rows($ra) <= 0) {

                // Insert data kedalam tebel rencana_angsuran dengan angsuran_ke>0
                $sqlinput = mysqli_query($koneksi, "INSERT INTO `rencana_angsuran_$lokasi`(`id`, `loan_id`, `angsuran_ke`,
                `jatuh_tempo`, `wajib_pokok`, `wajib_jasa`, `target_pokok`, `target_jasa`, `lu`, `id_user`) VALUES
                (null,'$pk[id]','$ke','$tglang','$wapok','$wajas','$tarp','$tarj','$datetime','1')");
            }
        }

        // Ambil Data Transaksi
        $tansaksi_loan = mysqli_query($koneksi, "SELECT * FROM transaksi_$lokasi WHERE id_pinj='$pk[id]' AND idtp!='0' ORDER BY tgl_transaksi ASC, idtp ASC ");
        $sum_pokok = 0;
        $sum_jasa = 0;
        echo $pk['id'];
        echo " = ";
        $id_pinj = $pk['id'];
        $data_idtp = [];
        $nooo = 1;

        // Loop Data Transaksi
        while ($tr = mysqli_fetch_array($tansaksi_loan)) {
            if ($tr['rekening_kredit'] == $dend_kredit) continue;
            // Cek apakah idtp sudah ada didalam array $data_idtp
            if (in_array($tr['idtp'], $data_idtp)) continue;
            echo " | ";
            echo $tr['idtp'] . " ";

            $tgl_transaksi = $tr['tgl_transaksi'];

            $transpokok = mysqli_fetch_array(mysqli_query($koneksi, "select sum(jumlah) as jumlah from transaksi_$lokasi WHERE idtp='$tr[idtp]' AND `rekening_kredit`='$poko_kredit'"));
            $realisasi_pokok = $transpokok['jumlah'] ?: 0;
            $sum_pokok = $sum_pokok + $transpokok['jumlah'];
            $saldo_pokok = $alokasi - $sum_pokok;

            $transjasa = mysqli_fetch_array(mysqli_query($koneksi, "select sum(jumlah) as jumlah from transaksi_$lokasi WHERE idtp='$tr[idtp]' AND (`rekening_kredit`='$jasa_kredit' OR `rekening_kredit` LIKE '1.1.05.%')"));
            // $transjasa = mysqli_fetch_array(mysqli_query($koneksi, "select sum(jumlah) as jumlah from transaksi_$lokasi WHERE idtp='$tr[idtp]' AND (`rekening_kredit`='$jasa_kredit')" ));
            $realisasi_jasa = $transjasa['jumlah'] ?: 0;
            $sum_jasa = $sum_jasa + $transjasa['jumlah'];
            $saldo_jasa = $tarj - $sum_jasa;

            $real = mysqli_query($koneksi, "SELECT * FROM real_angsuran_$lokasi WHERE loan_id='$id_pinj' ORDER BY tgl_transaksi DESC, id DESC");
            if (mysqli_num_rows($real) > 0) {
                $real_a = mysqli_fetch_array($real);
                $saldo_pokok = $real_a['saldo_pokok'] - $transpokok['jumlah'];
                $saldo_jasa = $real_a['saldo_jasa'] - $transjasa['jumlah'];
                $sum_pokok = $real_a['sum_pokok'] + $transpokok['jumlah'];
                $sum_jasa = $real_a['sum_jasa'] + $transjasa['jumlah'];
            }

            $ra = mysqli_fetch_array(mysqli_query($koneksi, "select * from rencana_angsuran_$lokasi WHERE loan_id='$id_pinj' AND jatuh_tempo<='$tgl_transaksi' 
                                    ORDER BY jatuh_tempo DESC, id DESC LIMIT 1"));
            $targetp = 0;
            $targetj = 0;
            if ($ra) {
                $targetp = $ra['target_pokok'];
                $targetj = $ra['target_jasa'];
            }
            $tunggakan_pokok = $targetp - $sum_pokok;
            $tunggakan_jasa = $targetj - $sum_jasa;

            if ($tunggakan_pokok < 0) {
                $tunggakan_pokok = 0;
            }

            if ($tunggakan_jasa < 0) {
                $tunggakan_jasa = 0;
            }

            $realisasi = mysqli_query($koneksi, "SELECT * FROM real_angsuran_$lokasi WHERE loan_id='$pk[id]' AND id='$tr[idtp]'");

            // Cek apakah data yang akan di input kedalam tabel real_angsuran_$lokasi sudah ada
            if (mysqli_num_rows($realisasi) <= 0) {
                $sqlinput2 = false;
                // Cek rekening_kredit apakah termasuk kedalam angsuran
                if ($tr['rekening_kredit'] == $poko_kredit || $tr['rekening_kredit'] == $jasa_kredit) {

                    // Input data kedalam tabel real_angsurann_$lokasi
                    $sqlinput2 = mysqli_query($koneksi, "INSERT INTO `real_angsuran_$lokasi` (`id`, `loan_id`, `tgl_transaksi`, `realisasi_pokok`, `realisasi_jasa`, 
                    `sum_pokok`, `sum_jasa`, `saldo_pokok`, `saldo_jasa`, `tunggakan_pokok`, `tunggakan_jasa`, `lu`, `id_user`) VALUES ('$tr[idtp]','$pk[id]',
                    '$tgl_transaksi','$realisasi_pokok','$realisasi_jasa','$sum_pokok','$sum_jasa','$saldo_pokok','$saldo_jasa','$tunggakan_pokok','$tunggakan_jasa',
                    '$datetime','1')");
                }
                if (!$sqlinput2) {
                    var_dump($koneksi->error);
                    echo "<br>";
                    var_dump("INSERT INTO `real_angsuran_$lokasi` (`id`, `loan_id`, `tgl_transaksi`, `realisasi_pokok`, `realisasi_jasa`, 
                    `sum_pokok`, `sum_jasa`, `saldo_pokok`, `saldo_jasa`, `tunggakan_pokok`, `tunggakan_jasa`, `lu`, `id_user`) VALUES ('$tr[idtp]','$pk[id]',
                    '$tgl_transaksi','$realisasi_pokok','$realisasi_jasa','$sum_pokok','$sum_jasa','$saldo_pokok','$saldo_jasa','$tunggakan_pokok','$tunggakan_jasa',
                    '$datetime','1')");
                    die;
                }

                var_dump($sqlinput2);
            }

            $data_idtp[] = $tr['idtp'];
        }
        echo "<br>";
    }

    if (mysqli_num_rows($pinjaman_kelompok) > 0) {
        echo "====================================================================================";
        echo "<script>if (true) {
            setTimeout(function() {
                document.querySelector('#migrate').click()
            }, 500)
        }</script>";
    }
} else {
    ?>

    <form action="" method="get">
        <input type="text" name="lokasi" id="lokasi" placeholder="Lokasi" autofocus>
        <input type="text" name="where" id="where" placeholder="WHERE field='key'" value="1">
        <button type="submit" name="migrate" id="migrate">Run</button>
    </form>

<?php
}
?>
<a href='?'>back</a>