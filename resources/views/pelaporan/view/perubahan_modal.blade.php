@php
    $t_saldo = 0;
@endphp
@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>LAPORAN PERUBAHAN MODAL</b>
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

    <table width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(232, 232, 232)">
            <th class="l t" width="5%" height="20">No</th>
            <th class="l t" width="55%">Rekening Modal</th>
            <th class="l t" width="20%">&nbsp;</th>
            <th class="l r t" width="20%">&nbsp;</th>
        </tr>

        @foreach ($rekening as $rek)
            @php
                if ($rek->kode_akun == '3.2.02.01') {
                    $saldo = $keuangan->laba_rugi($tgl_kondisi);
                } else {
                    $saldo = $keuangan->komSaldo($rek);
                }

                $t_saldo += $saldo;
            @endphp
            <tr>
                <td class="l t" align="center">{{ $loop->iteration }}</td>
                <td class="l t">{{ $rek->nama_akun }}</td>
                <td class="l t" align="right">{{ number_format($saldo, 2) }}</td>
                <td class="l t r">&nbsp;</td>
            </tr>
        @endforeach

        <tr>
            <td class="l t b" colspan="2" height="15">&nbsp;</td>
            <td class="l t b" align="right">{{ number_format($t_saldo, 2) }}</td>
            <td class="l t r b">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>
    </table>
@endsection
