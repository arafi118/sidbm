<?php

$koneksi = mysqli_connect('103.177.95.90', 'sidbm', 'cpanelDbm2026', 'sidbm_apps');

$query = mysqli_query($koneksi, 'SELECT * FROM kecamatan');
foreach ($query as $kec) {
    echo "DROP TRIGGER IF EXISTS create_saldo_{$kec['id']};<br>";
    echo "DROP TRIGGER IF EXISTS delete_saldo_{$kec['id']};<br>";
    echo "DROP TRIGGER IF EXISTS update_saldo_{$kec['id']};<br>";
    echo "DROP TRIGGER IF EXISTS update_tanggal_{$kec['id']};<br>";

    echo '<br>';
}
