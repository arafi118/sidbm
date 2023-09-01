@php
    use App\Utils\Tanggal;
    use App\Utils\Inventaris;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    @foreach ($inventaris as $rek)
        @if ($rek->lev4 != '1')
            <div class="break"></div>
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 10px;">
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
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tgl Beli</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Id</th>
                <th rowspan="2">Kondisi</th>
                <th rowspan="2">Unit</th>
                <th rowspan="2">Harga Satuan</th>
                <th rowspan="2">Harga Perolehan</th>
                <th rowspan="2">Umur Eko.</th>
                <th rowspan="2">Satuan Susut</th>
                <th colspan="2">Tahun Ini</th>
                <th colspan="2">s.d. Tahun Ini</th>
                <th rowspan="2">Nilai Buku</th>
            </tr>
            <tr>
                <th>Umur</th>
                <th>Biaya</th>
                <th>Umur</th>
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
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">{{ Tanggal::tglIndo($inv->tgl_beli) }}</td>
                    <td>{{ $nama_barang }}</td>
                    <td align="center">{{ $inv->id }}</td>
                    <td align="center">{{ $inv->status }}</td>
                    <td align="center">{{ $inv->unit }}</td>
                    <td align="right">{{ number_format($inv->harsat) }}</td>
                    <td align="right">{{ number_format($inv->harsat * $inv->unit) }}</td>

                    @if ($rek->lev4 == '1')
                        <td colspan="6"></td>
                        <td align="right">{{ number_format($inv->harsat * $inv->unit) }}</td>
                    @else
                        @php
                            $satuan_susut = $inv->harsat <= 0 ? 0 : (float) (($inv->harsat / $inv->umur_ekonomis) * $inv->unit);
                            $pakai_lalu = Inventaris::bulan($inv->tgl_beli, $tahun - 1 . '-12-31');
                            $nilai_buku = Inventaris::nilaiBuku($tgl_kondisi, $inv);
                            
                            if (!($inv->status == 'Baik') && $tgl_kondisi >= $inv->tgl_validasi) {
                                $umur = Inventaris::bulan($inv->tgl_beli, $inv->tgl_validasi);
                            } else {
                                $umur = Inventaris::bulan($inv->tgl_beli, $tgl_kondisi);
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
                        @endphp

                        <td align="center">{{ $inv->umur_ekonomis }}</td>
                        <td align="right">{{ number_format($satuan_susut) }}</td>
                        <td align="center">{{ $umur_pakai }}</td>
                        <td align="right">{{ number_format($penyusutan) }}</td>
                        <td align="center">{{ $akum_umur }}</td>
                        <td align="right">{{ number_format($akum_susut) }}</td>
                        <td align="right">{{ number_format($nilai_buku) }}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endforeach
@endsection
