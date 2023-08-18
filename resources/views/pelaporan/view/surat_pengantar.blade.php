@extends('pelaporan.layout.base')

@section('content')
    <table border="0">
        <tr>
            <td>Nomor</td>
            <td width="150">: ______________________</td>
            <td width="257" align="right">{{ $kec->nama_kec }}, {{ $tgl }}</td>
        </tr>
        <tr>
            <td width="65">Lampiran</td>
            <td width="150">: 1 Bendel</td>
        </tr>
        <tr>
            <td width="65">Perihal</td>
            <td width="150">: Laporan Keuangan</td>
        </tr>
        <tr>
            <td width="65">&nbsp;</td>
            <td width="150" style="padding-left: 8px;">
                <u>Sampai Dengan {{ $sub_judul }}</u>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="15"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="2" align="left" style="padding-left: 8px;">
                <div><b>Kepada Yth.</b></div>
                <div><b>Kepala Dinas PMD {{ $nama_kabupaten }}</b></div>
                <div><b>Di {{ $kab->alamat_kab }}.</b></div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="15"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="2" style="padding-left: 8px; text-align: justify;">
                <div>Dengan Hormat,</div>
                <div>
                    Bersama ini kami sampaikan Laporan Keuangan {{ $nama_lembaga }} {{ $kec->nama_kec }} sampai dengan
                    {{ $sub_judul }} sebagai berikut:
                    <ol>
                        <li>Laporan Neraca</li>
                        <li>Laporan Rugi/Laba</li>
                        <li>Neraca Saldo</li>
                        <li>Laporan Hutang</li>
                        <li>Laporan Piutang</li>
                    </ol>
                </div>
                <div>
                    Demikian laporan kami sampaikan, atas perhatiannya kami ucapkan terima kasih.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="15"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td align="center">
                <div>{{ $nama_lembaga }} {{ $kec->nama_kec }}</div>
                <div>{{ $kec->sebutan_level_1 }} DBM,</div>
                <br>
                <br>
                <br>
                <br>
                <div>
                    <b>{{ $dir->namadepan . ' ' . $dir->namabelakang }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div>
                    Tembusan :
                    <ol>
                        <li>Arsip</li>
                    </ol>
                </div>
            </td>
        </tr>
    </table>
@endsection
