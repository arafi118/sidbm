<?php
$koneksi = mysqli_connect('103.177.95.90', 'dbm_sidbm', 'dbm_sidbm', 'dbm_laravel');

function startWith($string, $startString)
{
    $string = (string) $string;
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function pembulatan($angka, $pembulatan)
{
    $angka = round($angka);

    $sistem = 'auto';
    if (startWith($pembulatan, '+')) {
        $sistem = 'keatas';
        $pembulatan = intval($pembulatan);
    }

    if (startWith($pembulatan, '-')) {
        $sistem = 'kebawah';
        $pembulatan = intval($pembulatan * -1);
    }

    $ratusan = substr($angka, -strlen($pembulatan / 2));
    $nilai_tengah = $pembulatan / 2;

    if ($sistem == 'keatas') {
        $akhir = $angka + ($pembulatan - $ratusan);
    }

    if ($sistem == 'kebawah') {
        $akhir = $angka - $ratusan;
    }

    if ($sistem == 'auto') {
        if ($ratusan <= $nilai_tengah) {
            $akhir = $angka - $ratusan;
        } else {
            $akhir = $angka + ($pembulatan - $ratusan);
        }
    }

    return $akhir;
}

if (isset($_GET['generate']) && isset($_GET['lokasi'])) {
    $lokasi = htmlspecialchars($_GET['lokasi']);
    $kolom = htmlspecialchars($_GET['kolom']);
    $ops = htmlspecialchars($_GET['ops']);
    $value = htmlspecialchars($_GET['value']);
    $datetime = date('Y-m-d H:i:s');

    $kec = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kecamatan WHERE id='$lokasi'"));

    $where = "WHERE (status='A' OR status='W' OR status='L' OR status='H' OR status='R')";
    if (!($kolom == '' && $ops == '') && $value != '') {

        if ($ops == 'IN') {
            $value = "($value)";
        }

        $where .= " AND $kolom $ops $value";
    }

    $per_page = 25;
    if (isset($_GET['limit']) && isset($_GET['start'])) {
        $start = $_GET['start'] + $_GET['limit'];
    } else {
        $start = 0;
    }

    $pinjaman_kelompok = mysqli_query($koneksi, "SELECT * FROM pinjaman_kelompok_$lokasi $where ORDER BY id ASC LIMIT $start, $per_page");
    foreach ($pinjaman_kelompok as $pinkel) {
        $kel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelompok_$lokasi WHERE id='$pinkel[id_kel]'"));
        $desa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM desa WHERE kd_desa='$kel[desa]'"));

        $del_re = mysqli_query($koneksi, "DELETE FROM real_angsuran_$lokasi WHERE loan_id=$pinkel[id]");
        $del_ra = mysqli_query($koneksi, "DELETE FROM rencana_angsuran_$lokasi WHERE loan_id=$pinkel[id]");

        if ($pinkel['status'] == 'P') {
            $alokasi = $pinkel['proposal'];
            $tgl_cair = $pinkel['tgl_proposal'];
        } elseif ($pinkel['status'] == 'V') {
            $alokasi = $pinkel['verifikasi'];
            $tgl_cair = $pinkel['tgl_verifikasi'];
        } elseif ($pinkel['status'] == 'W') {
            $alokasi = $pinkel['alokasi'];
            $tgl_cair = $pinkel['tgl_cair'];

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinkel['tgl_tunggu'];
            }
        } else {
            $alokasi = $pinkel['alokasi'];
            $tgl_cair = $pinkel['tgl_cair'];

            if ($tgl_cair == "0000-00-00") {
                $tgl_cair = $pinkel['tgl_tunggu'];
            }
        }

        $tgl_angsur = $tgl_cair;
        $tanggal_cair = date('d', strtotime($tgl_cair));

        if ($desa['jadwal_angsuran_desa'] > 0) {
            $angsuran_desa = $desa['jadwal_angsuran_desa'];
            if ($angsuran_desa > 0) {
                $tgl_pinjaman = date('Y-m', strtotime($tgl_cair));
                $tgl_cair = $tgl_pinjaman . '-' . $angsuran_desa;
            }
        }

        if ($kec['batas_angsuran'] > 0) {
            $batas_tgl_angsuran = $kec['batas_angsuran'];
            if ($tanggal_cair >= $batas_tgl_angsuran) {
                $tgl_cair = date('Y-m-d', strtotime('+1 month', strtotime($tgl_cair)));
            }
        }
        $tgllalu = $tgl_cair;

        $jenis_jasa = $pinkel['jenis_jasa'];
        $jangka = $pinkel['jangka'];
        $sa_pokok = $pinkel['sistem_angsuran'];
        $sa_jasa = $pinkel['sa_jasa'];
        $pros_jasa = $pinkel['pros_jasa'];

        $rek_pokok = ['1.1.03.01', '1.1.03.02', '1.1.03.03'];
        $rek_jasa = ['4.1.01.01', '4.1.01.02', '4.1.01.03', '1.1.03.04', '1.1.03.05', '1.1.03.06'];
        $rek_denda = ['4.1.01.04', '4.1.01.05', '4.1.01.06'];

        $sistem_pokok = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM sistem_angsuran WHERE id='$sa_pokok'"))['sistem'];
        $sistem_jasa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM sistem_angsuran WHERE id='$sa_jasa'"))['sistem'];

        if ($sa_pokok == 11) {
            $tempo_pokok        = ($jangka) - 24 / $sistem_pokok;
        } else if ($sa_pokok == 14) {
            $tempo_pokok        = ($jangka) - 3 / $sistem_pokok;
        } else if ($sa_pokok == 15) {
            $tempo_pokok        = ($jangka) - 2 / $sistem_pokok;
        } else if ($sa_pokok == 20) {
            $tempo_pokok        = ($jangka) - 12 / $sistem_pokok;
        } else {
            $tempo_pokok        = floor($jangka / $sistem_pokok);
        }

        if ($sa_jasa == 11) {
            $tempo_jasa        = ($jangka) - 24 / $sistem_jasa;
        } else if ($sa_jasa == 14) {
            $tempo_jasa        = ($jangka) - 3 / $sistem_jasa;
        } else if ($sa_jasa == 15) {
            $tempo_jasa        = ($jangka) - 2 / $sistem_jasa;
        } else if ($sa_jasa == 20) {
            $tempo_jasa        = ($jangka) - 12 / $sistem_jasa;
        } else {
            $tempo_jasa        = floor($jangka / $sistem_jasa);
        }

        $ra = [];
        $alokasi_pokok = $alokasi;
        for ($j = 1; $j <= $jangka; $j++) {
            $sisa = $j % $sistem_jasa;
            $ke = $j / $sistem_jasa;
            $alokasi_jasa = $alokasi_pokok * ($pros_jasa / 100);
            $wajib_jasa = $alokasi_jasa / $tempo_jasa;

            if ($kec['pembulatan'] != '5000') {
                $wajib_jasa = pembulatan($wajib_jasa, (string) $kec['pembulatan']);
            }

            $sum_jasa = $wajib_jasa * ($tempo_jasa - 1);

            if ($sisa == 0 and $ke != $tempo_jasa) {
                $angsuran_jasa = $wajib_jasa;
            } elseif ($sisa == 0 and $ke == $tempo_jasa) {
                $angsuran_jasa = $alokasi_jasa - $sum_jasa;
            } else {
                $angsuran_jasa = 0;
            }

            if ($jenis_jasa == '2') {
                $angsuran_jasa = $wajib_jasa;
                $alokasi_pokok -= $ra[$j]['pokok'];
            }

            $ra[$j]['jasa'] = $angsuran_jasa;
        }

        for ($i = 1; $i <= $jangka; $i++) {
            $sisa = $i % $sistem_pokok;
            $ke = $i / $sistem_pokok;

            $wajib_pokok = ($alokasi / 10) - $ra[$i]['jasa'];
            if ($jangka == 24) {
                $wajib_pokok = pembulatan((($alokasi / 10) - $ra[$i]['jasa']) / 2, -500);

                if ($alokasi > 1000000) {
                    $wajib_pokok = pembulatan((($alokasi / 10) - $ra[$i]['jasa']) / 2, 5000);
                }

                if ($alokasi < 20000000) {
                    if ($alokasi >= 8000000) {
                        $wajib_pokok -= 5000;
                    }

                    if ($alokasi == 12000000 || $alokasi >= 14000000) {
                        $wajib_pokok -= 5000;
                    }

                    if ($alokasi == 18000000 || $alokasi == 6000000) {
                        $wajib_pokok -= 5000;
                    }
                }
            }

            if ($kec['pembulatan'] != '5000') {
                $wajib_pokok = pembulatan($alokasi / $tempo_pokok, (string) $kec['pembulatan']);
            }

            $sum_pokok = $wajib_pokok * ($tempo_pokok - 1);
            if ($sisa == 0 and $ke != $tempo_pokok) {
                $angsuran_pokok = $wajib_pokok;
            } elseif ($sisa == 0 and $ke == $tempo_pokok) {
                $angsuran_pokok = $alokasi - $sum_pokok;
            } else {
                $angsuran_pokok = 0;
            }

            $ra[$i]['pokok'] = $angsuran_pokok;
        }

        $ra['alokasi'] = $alokasi;

        $kolom = "loan_id, angsuran_ke, jatuh_tempo, wajib_pokok, wajib_jasa, target_pokok, target_jasa, lu, id_user";
        $values = "'$pinkel[id]', '0', '$tgl_cair', '0', '0', '0', '0', '$datetime', '1'";
        $insert_ra = "INSERT INTO rencana_angsuran_$lokasi($kolom) VALUES($values)";

        $target_pokok = 0;
        $target_jasa = 0;
        for ($x = 1; $x <= $jangka; $x++) {
            $bulan  = substr($tgl_cair, 5, 2);
            $tahun  = substr($tgl_cair, 0, 4);

            if ($sa_pokok == 12) {
                $tambah = $x * 7;
                $penambahan = "+$tambah days";
            } else {
                $penambahan = "+$x month";
            }
            $jatuh_tempo = date('Y-m-d', strtotime($penambahan, strtotime($tgl_cair)));

            $pokok = $ra[$x]['pokok'];
            $jasa = $ra[$x]['jasa'];

            if ($x == 1) {
                $target_pokok = $pokok;
            } elseif ($x >= 2) {
                $target_pokok += $pokok;
            }
            if ($x == 1) {
                $target_jasa = $jasa;
            } elseif ($x >= 2) {
                $target_jasa += $jasa;
            }

            $insert_ra .= ", ('$pinkel[id]', '$x', '$jatuh_tempo', '$pokok', '$jasa', '$target_pokok', '$target_jasa', '$datetime', '1')";
        }

        $insert_ra .= ';';
        $rencana_angsuran = mysqli_query($koneksi, $insert_ra);

        $alokasi_pokok = $alokasi;
        $alokasi_jasa = $target_jasa;

        $data_idtp = [];
        $sum_pokok = 0;
        $sum_jasa = 0;

        $transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi_$lokasi WHERE id_pinj='$pinkel[id]' AND idtp!='0' ORDER BY tgl_transaksi ASC, idtp ASC ");
        foreach ($transaksi as $trx) {
            if (in_array($trx['rekening_kredit'], $rek_denda)) continue;
            if (in_array($trx['idtp'], $data_idtp)) continue;

            $tgl_transaksi = $trx['tgl_transaksi'];
            $transaksi_idtp = mysqli_query($koneksi, "SELECT * FROM transaksi_$lokasi WHERE idtp='$trx[idtp]'");

            $realisasi_pokok = 0;
            $realisasi_jasa = 0;
            foreach ($transaksi_idtp as $idtp) {
                if (in_array($idtp['rekening_kredit'], $rek_pokok)) {
                    $realisasi_pokok = $idtp['jumlah'];
                    $sum_pokok += $realisasi_pokok;
                    $alokasi_pokok -= $realisasi_pokok;
                }

                if (in_array($idtp['rekening_kredit'], $rek_jasa)) {
                    $realisasi_jasa = $idtp['jumlah'];
                    $sum_jasa += $realisasi_jasa;
                    $alokasi_jasa -= $realisasi_jasa;
                }
            }

            $ra = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM rencana_angsuran_$lokasi WHERE loan_id='$pinkel[id]' AND jatuh_tempo<='$tgl_transaksi' ORDER BY jatuh_tempo DESC, id DESC LIMIT 1"));

            $target_pokok = 0;
            $target_jasa = 0;
            if ($ra) {
                $target_pokok = $ra['target_pokok'];
                $target_jasa = $ra['target_jasa'];
            }
            $tunggakan_pokok = $target_pokok - $sum_pokok;
            $tunggakan_jasa = $target_jasa - $sum_jasa;

            if ($tunggakan_pokok < 0) {
                $tunggakan_pokok = 0;
            }

            if ($tunggakan_jasa < 0) {
                $tunggakan_jasa = 0;
            }

            $real_angsuran = mysqli_query($koneksi, "INSERT INTO `real_angsuran_$lokasi` 
                    (`id`, `loan_id`, `tgl_transaksi`, `realisasi_pokok`, `realisasi_jasa`,`sum_pokok`, `sum_jasa`, `saldo_pokok`, `saldo_jasa`, `tunggakan_pokok`, `tunggakan_jasa`, `lu`, `id_user`) VALUES 
                    ('$trx[idtp]','$pinkel[id]','$tgl_transaksi','$realisasi_pokok','$realisasi_jasa','$sum_pokok','$sum_jasa','$alokasi_pokok','$alokasi_jasa','$tunggakan_pokok','$tunggakan_jasa','$datetime','1')");

            $data_idtp[] = $trx['idtp'];
        }
    }

    $url = $_SERVER['REQUEST_URI'];
    $url = parse_url($url);

    parse_str($url['query'], $queryString);
    $queryString['start'] = $start + $per_page;

    $newQueryString = http_build_query($queryString);

    if (mysqli_num_rows($pinjaman_kelompok) < $per_page) {
        header('Location: ' . $url['path']);
        exit;
    }

    echo '<script>window.location.href = "' . $url['path'] . '/' . $newQueryString . '"</script>';
    exit;
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <title>GENERATE</title>
    </head>

    <body>

        <div class="container mt-3">
            <form class="row g-3" method="GET">
                <div class="mb-3 col-md-2">
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="number" class="form-control" id="lokasi" name="lokasi" required placeholder="ID Kecamatan">
                    </div>
                </div>
                <div class="mb-3 col-md-2">
                    <div class="form-group">
                        <label for="kolom">WHERE</label>
                        <input type="text" class="form-control" id="kolom" name="kolom" placeholder="Nama Kolom">
                    </div>
                </div>
                <div class="mb-3 col-md-2">
                    <div class="form-group">
                        <label for="ops">Option</label>
                        <select class="form-control" id="ops" name="ops">
                            <option value="">None</option>
                            <option value="=">( = )</option>
                            <option value="LIKE">( LIKE )</option>
                            <option value="IN">( IN )</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 col-md-4">
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" class="form-control" id="value" name="value" placeholder="1" value="1">
                    </div>
                </div>
                <div class="mb-3 col-md-2 pt-2">
                    <button type="submit" name="generate" class="btn btn-primary mt-4">Generate</button>
                </div>
            </form>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php } ?>