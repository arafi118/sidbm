@php
    use App\Utils\Tanggal;
    $nomor = 1;

    $t_alokasi = 0;
    $t_tunggakan_pokok = 0;
    $t_tunggakan_jasa = 0;
@endphp

@extends('pelaporan.layout.base')

@section('content')
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr>
            <td colspan="3" align="center">
                <div style="font-size: 18px;">
                    <b>DAFTAR TAGIHAN HARI INI</b>
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

    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
        <tr style="background: rgb(230, 230, 230); font-weight: bold;">
            <th class="t l b" height="20" width="3%">No</th>
            <th class="t l b" width="20%">Kelompok - Loan ID.</th>
            <th class="t l b" width="14%">Ketua</th>
            <th class="t l b" width="25%">Alamat</th>
            <th class="t l b" width="8%">Tgl Cair</th>
            <th class="t l b" width="10%">Alokasi</th>
            <th class="t l b" width="10%">Tunggakan Pokok</th>
            <th class="t l b r" width="10%">Tunggakan Jasa</th>
        </tr>

        @foreach ($pinjaman as $pinkel)
            @if ($pinkel->target)
                @php
                    $sum_pokok = 0;
                    $sum_jasa = 0;

                    if ($pinkel->saldo) {
                        $sum_pokok = $pinkel->saldo->sum_pokok;
                        $sum_jasa = $pinkel->saldo->sum_jasa;
                    }

                    $nunggak_pokok = $pinkel->target->target_pokok - $sum_pokok;
                    $nunggak_jasa = $pinkel->target->target_jasa - $sum_jasa;

                    $t_alokasi += $pinkel->alokasi;
                    $t_tunggakan_pokok += $nunggak_pokok;
                    $t_tunggakan_jasa += $nunggak_jasa;
                @endphp

                @if ($nunggak_pokok > 0 || $nunggak_jasa > 0)
                    <tr>
                        <td class="t l b" align="center">{{ $nomor++ }}</td>
                        <td class="t l b">
                            {{ $pinkel->kelompok->nama_kelompok }} - {{ $pinkel->id }}
                        </td>
                        <td class="t l b">{{ $pinkel->kelompok->ketua }}</td>
                        <td class="t l b">
                            {{ $pinkel->kelompok->alamat_kelompok }} {{ $pinkel->kelompok->d->nama_desa }}
                        </td>
                        <td class="t l b" align="center">{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
                        <td class="t l b" align="right">{{ number_format($pinkel->alokasi) }}</td>
                        <td class="t l b" align="right">{{ number_format($nunggak_pokok) }}</td>
                        <td class="t l b r" align="right">{{ number_format($nunggak_jasa) }}</td>
                    </tr>
                @endif
            @endif
        @endforeach

        <tr>
            <td colspan="8" style="padding: 0px !important;">
                <table class="p" border="0" width="100%" cellspacing="0" cellpadding="0"
                    style="font-size: 11px;">
                    <tr style="background: rgb(74, 74, 74); font-weight: bold; color: #fff;" class="t l b r">
                        <td height="15" width="70%" align="center">J U M L A H</td>
                        <td align="right" width="10%">{{ number_format($t_alokasi) }}</td>
                        <td align="right" width="10%">{{ number_format($t_tunggakan_pokok) }}</td>
                        <td align="right" width="10%">{{ number_format($t_tunggakan_jasa) }}
                    </tr>
                </table>

                <div style="margin-top: 16px;"></div>
                {!! json_decode(str_replace('{tanggal}', $tanggal_kondisi, $kec->ttd->tanda_tangan_pelaporan), true) !!}
            </td>
        </tr>
    </table>
@endsection
