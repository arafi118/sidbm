@php
    use App\Utils\Tanggal;
    use App\Utils\Inventaris;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($inventaris as $rek)
        @php
            $t_unit = 0;
            $t_harga = 0;
            $t_penyusutan = 0;
            $t_akum_susut = 0;
            $t_nilai_buku = 0;
            
            $j_unit = 0;
            $j_harga = 0;
            $j_penyusutan = 0;
            $j_akum_susut = 0;
            $j_nilai_buku = 0;
            
            $no = 1;
        @endphp
        @if ($rek->lev4 != '1')
            <div class="break"></div>
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
            <tr>
                <td colspan="3" align="center">
                    <div style="font-size: 18px;">
                        <b>Daftar {{ $rek->nama_akun }}</b>
                    </div>
                    <div style="font-size: 16px;">
                        <b>{{ strtoupper($sub_judul) }}</b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="5"></td>
            </tr>

        </table>
        <table border="1" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
            <tr style="background: rgb(232, 232, 232)">
                <th rowspan="2" width="10">No</th>
                <th rowspan="2" width="40">Tgl Beli</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2" width="10">Id</th>
                <th rowspan="2" width="30">Kondisi</th>
                <th rowspan="2" width="15">Unit</th>
                <th rowspan="2" width="55">Harga Satuan</th>
                <th rowspan="2" width="55">Harga Perolehan</th>
                <th rowspan="2" width="20">Umur Eko.</th>
                <th rowspan="2" width="55">Satuan Susut</th>
                <th colspan="2" width="55">Tahun Ini</th>
                <th colspan="2" width="55">s.d. Tahun Ini</th>
                <th rowspan="2" width="55">Nilai Buku</th>
            </tr>
            <tr style="background: rgb(232, 232, 232)">
                <th width="15">Umur</th>
                <th>Biaya</th>
                <th width="15">Umur</th>
                <th>Biaya</th>
            </tr>
            @foreach ($rek->inventaris as $inv)
                @php
                    $nama_barang = $inv->nama_barang;
                    $warna = '0, 0, 0';
                    if (!($inv->status == 'Baik') && $tgl_kondisi >= $inv->tgl_validasi) {
                        $nama_barang .= ' (' . $inv->status . ' ' . Tanggal::tglIndo($inv->tgl_validasi) . ')';
                        $warna = '255, 0, 0';
                    }
                @endphp
                <tr style="color: rgb({{ $warna }})">
                    @if ($rek->lev4 == '1')
                        @php
                            $t_unit += $inv->unit;
                            $t_harga += $inv->harsat * $inv->unit;
                            $t_nilai_buku += $inv->harsat * $inv->unit;
                            
                            $nilai_buku = $inv->harsat * $inv->unit;
                            if ($inv->status == 'Dijual' || $inv->status == 'Hapus') {
                                $nilai_buku = '0';
                            }
                            
                            if ($inv->status == 'Dijual' || $inv->status == 'Hilang' || $inv->status == 'Dihapus') {
                                $j_unit += $inv->unit;
                                $j_harga += $inv->harsat * $inv->unit;
                                $j_nilai_buku += $inv->harsat * $inv->unit;
                            }
                        @endphp
                        <td align="center">{{ $no++ }}</td>
                        <td align="center">{{ Tanggal::tglIndo($inv->tgl_beli) }}</td>
                        <td>{{ $nama_barang }}</td>
                        <td align="center">{{ $inv->id }}</td>
                        <td align="center">{{ $inv->status }}</td>
                        <td align="center">{{ $inv->unit }}</td>
                        <td align="right">{{ number_format($inv->harsat, 2) }}</td>
                        <td align="right">{{ number_format($inv->harsat * $inv->unit, 2) }}</td>
                        <td colspan="6"></td>
                        <td align="right">{{ number_format($nilai_buku, 2) }}</td>
                    @else
                        @php
                            $satuan_susut = $inv->harsat <= 0 ? 0 : round(($inv->harsat / $inv->umur_ekonomis) * $inv->unit, 2);
                            $pakai_lalu = Inventaris::bulan($inv->tgl_beli, $tahun - 1 . '-12-31');
                            $nilai_buku = Inventaris::nilaiBuku($tgl_kondisi, $inv);
                            
                            if (!($inv->status == 'Baik') && $tgl_kondisi >= $inv->tgl_validasi) {
                                $umur = Inventaris::bulan($inv->tgl_beli, $inv->tgl_validasi);
                            } else {
                                $umur = Inventaris::bulan($inv->tgl_beli, $tgl_kondisi);
                            }
                            
                            $_satuan_susut = $satuan_susut;
                            if ($umur >= $inv->umur_ekonomis) {
                                $harga = $inv->harsat * $inv->unit;
                                $_susut = $satuan_susut * ($inv->umur_ekonomis - 1);
                                $satuan_susut = $harga - $_susut - 1;
                            }
                            
                            $susut = $satuan_susut * $umur;
                            if ($umur >= $inv->umur_ekonomis && $inv->harsat * $inv->unit > 0) {
                                $akum_umur = $inv->umur_ekonomis;
                                $_akum_susut = $inv->harsat * $inv->unit;
                                $akum_susut = $_akum_susut - 1;
                                $nilai_buku = 1;
                            } else {
                                $akum_umur = $umur;
                                $akum_susut = $susut;
                            
                                if ($nilai_buku < 0) {
                                    $nilai_buku = 1;
                                }
                            }
                            
                            $umur_pakai = $akum_umur - $pakai_lalu;
                            $penyusutan = $satuan_susut * $umur_pakai;
                            
                            if (($inv->status == 'Hilang' and $tgl_kondisi >= $inv->tgl_validasi) || ($inv->status == 'Dijual' && $tgl_kondisi >= $inv->tgl_validasi) || ($inv->status == 'Hapus' && $tgl_kondisi >= $inv->tgl_validasi)) {
                                $akum_susut = $inv->harsat * $inv->unit;
                                $nilai_buku = 0;
                                $penyusutan = 0;
                                $umur_pakai = 0;
                            }
                            
                            if ($inv->status == 'Rusak' and $tgl_kondisi >= $inv->tgl_validasi) {
                                $akum_susut = $inv->harsat * $inv->unit - 1;
                                $nilai_buku = 1;
                                $penyusutan = 0;
                                $umur_pakai = 0;
                            }
                            
                            if ($umur_pakai >= 0 && $inv->harsat * $inv->unit > 0) {
                                $penyusutan = $penyusutan;
                            } else {
                                $umur_pakai = 0;
                                $penyusutan = 0;
                            }
                            
                            if ($akum_umur == $inv->umur_ekonomis && $umur_pakai > '0') {
                                $penyusutan = $_satuan_susut * ($umur_pakai - 1) + $satuan_susut;
                            }
                            
                            $t_unit += $inv->unit;
                            $t_harga += $inv->harsat * $inv->unit;
                            $t_penyusutan += $penyusutan;
                            $t_akum_susut += $akum_susut;
                            $t_nilai_buku += $nilai_buku;
                            
                            $tahun_validasi = substr($inv->tgl_validasi, 0, 4);
                        @endphp

                        @if (($rek->lev4 == 1 || $rek->lev4 == 4) && $nilai_buku == 0 && $tahun_validasi < $tahun)
                            @php
                                $j_unit += $inv->unit;
                                $j_harga += $inv->harsat * $inv->unit;
                                $j_penyusutan += $penyusutan;
                                $j_akum_susut += $akum_susut;
                                $j_nilai_buku += $nilai_buku;
                            @endphp
                        @else
                            <td align="center">{{ $no++ }}</td>
                            <td align="center">{{ Tanggal::tglIndo($inv->tgl_beli) }}</td>
                            <td>{{ $nama_barang }}</td>
                            <td align="center">{{ $inv->id }}</td>
                            <td align="center">{{ $inv->status }}</td>
                            <td align="center">{{ $inv->unit }}</td>
                            <td align="right">{{ number_format($inv->harsat, 2) }}</td>
                            <td align="right">{{ number_format($inv->harsat * $inv->unit, 2) }}</td>
                            <td align="center">{{ $inv->umur_ekonomis }}</td>
                            <td align="right">{{ number_format($_satuan_susut, 2) }}</td>
                            <td align="center">{{ $umur_pakai }}</td>
                            <td align="right">{{ number_format($penyusutan, 2) }}</td>
                            <td align="center">{{ $akum_umur }}</td>
                            <td align="right">{{ number_format($akum_susut, 2) }}</td>
                            <td align="right">{{ number_format($nilai_buku, 2) }}</td>
                        @endif
                    @endif
                </tr>
            @endforeach
            <tr>
                <td height="15" colspan="5">
                    Jumlah Daftar {{ $rek->nama_akun }} (Hapus, Hilang, Jual) s.d. Tahun {{ $tahun - 1 }}
                </td>
                <td align="center">{{ $j_unit }}</td>
                <td>&nbsp;</td>
                <td align="right">{{ number_format($j_harga, 2) }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right" colspan="2">{{ number_format($j_penyusutan, 2) }}</td>
                <td align="right" colspan="2">{{ number_format($j_akum_susut, 2) }}</td>
                <td align="right">{{ number_format($j_nilai_buku, 2) }}</td>
            </tr>
            <tr>
                <td height="15" colspan="5">
                    Jumlah
                </td>
                <td align="center">{{ number_format($t_unit, 2) }}</td>
                <td>&nbsp;</td>
                <td align="right">{{ number_format($t_harga, 2) }}</td>
                @if ($rek->lev4 == '1')
                    <td colspan="6">&nbsp;</td>
                @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right" colspan="2">{{ number_format($t_penyusutan, 2) }}</td>
                    <td align="right" colspan="2">{{ number_format($t_akum_susut, 2) }}</td>
                @endif
                <td align="right">{{ number_format($t_nilai_buku, 2) }}</td>
            </tr>
        </table>
    @endforeach
@endsection
