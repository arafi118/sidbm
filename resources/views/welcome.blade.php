<div>Kecamatan {{ $kec->nama_kec }} [{{ $kec->id }}], {{ $kec->kabupaten->nama_kab }}</div>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th>Nama</th>
        <th>Level</th>
        <th>Jabatan</th>
        <th>Username</th>
        <th>Password</th>
    </tr>
    @foreach ($users as $u)
        <tr>
            <td>{{ $u->namadepan . ' ' . $u->namabelakang }}</td>
            <td>{{ $u->l->nama_level }}</td>
            <td>{{ $u->j->nama_jabatan }}</td>
            <td>{{ $u->uname }}</td>
            <td>{{ $u->pass }}</td>
        </tr>
    @endforeach
</table>
<div style="text-align: right;">
    <a href="/" target="_blank">{{ $kec->web_kec }}</a>
</div>
