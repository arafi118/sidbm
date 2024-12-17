@php
    use App\Utils\Keuangan;
    $keuangan = new Keuangan();
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>NERACA</b>
                </div>
                <div style="font-size: 16px;">
                    <b>{{ strtoupper($sub_judul) }}</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="3"></td>
        </tr>
        <tr style="background: #000; color: #fff;">
            <td width="10%">Kode</td>
            <td width="70%">Nama Akun</td>
            <td align="right" width="20%">Saldo</td>
        </tr>
        <tr>
            <td colspan="3" height="1"></td>
        </tr>

        @foreach ($akun1 as $lev1)
            @php
                $sum_akun1 = 0;
            @endphp
            <tr style="background: rgb(74, 74, 74); color: #fff;">
                <td height="20" colspan="3" align="center">
                    <b>{{ $lev1->kode_akun }}. {{ $lev1->nama_akun }}</b>
                </td>
            </tr>
            @foreach ($lev1->akun2 as $lev2)
                <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                    <td>{{ $lev2->kode_akun }}.</td>
                    <td colspan="2">{{ $lev2->nama_akun }}</td>
                </tr>

                @foreach ($lev2->akun3 as $lev3)
                    @php
                        $sum_saldo = 0;
                    @endphp

                    @foreach ($lev3->rek as $rek)
                        @php
                            $saldo = $keuangan->komSaldo($rek);
                            if ($rek->kode_akun == '3.2.02.01') {
                                $saldo = $keuangan->laba_rugi($tgl_kondisi);
                            }

                            $sum_saldo += $saldo;
                        @endphp
                    @endforeach
                    @php
                        $bg = 'rgb(230, 230, 230)';
                        if ($loop->iteration % 2 == 0) {
                            $bg = 'rgba(255, 255, 255)';
                        }

                        if ($lev1->lev1 == '1') {
                            $debit += $sum_saldo;
                        } else {
                            $kredit += $sum_saldo;
                        }

                        $sum_akun1 += $sum_saldo;
                    @endphp
                    <tr style="background: {{ $bg }};">
                        <td>{{ $lev3->kode_akun }}.</td>
                        <td>{{ $lev3->nama_akun }}</td>
                        @if ($sum_saldo < 0)
                            <td align="right">({{ number_format($sum_saldo * -1, 2) }})</td>
                        @else
                            <td align="right">{{ number_format($sum_saldo, 2) }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
            <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                <td height="15" colspan="2" align="left">
                    <b>Jumlah {{ $lev1->nama_akun }}</b>
                </td>
                <td align="right">{{ number_format($sum_akun1, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" height="1"></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(167, 167, 167); font-weight: bold;">
                        <td height="15" width="80%" align="left">
                            <b>Jumlah Liabilitas + Ekuitas </b>
                        </td>
                        <td align="right" width="20%">{{ number_format($kredit, 2) }}</td>
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>
    </table>
@endsection
