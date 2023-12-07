@php
    $liabilitas = 0;
@endphp
@foreach ($akun1 as $lev1)
    @php
        $aset = 0;
    @endphp
    <div class="card mb-3">
        <div class="card-body p-3 pb-0">
            <table class="table table-striped">
                <thead>
                    <tr class="bg-dark text-light">
                        <th width="10%">Kode Akun</th>
                        <th>Nama Akun</th>
                        <th width="30%">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lev1->akun2 as $lev2)
                        @foreach ($lev2->akun3 as $lev3)
                            @php
                                $saldo_akun = 0;
                            @endphp
                            @foreach ($lev3->rek as $rek)
                                @php
                                    $tb = 'tb' . $tahun_lalu;
                                    $tbk = 'tbk' . $tahun_lalu;

                                    if ($lev1->lev1 <= 1) {
                                        $saldo_awal = $rek->$tb - $rek->$tbk;
                                        $_saldo = $saldo_awal + ($rek->saldo->debit - $rek->saldo->kredit);
                                    } else {
                                        $saldo_awal = $rek->$tbk - $rek->$tb;
                                        $_saldo = $saldo_awal + ($rek->saldo->kredit - $rek->saldo->debit);
                                    }

                                    if ($rek->kode_akun == '3.2.02.01') {
                                        $pendapatan = 0;
                                        $biaya = 0;
                                        foreach ($surplus as $sp) {
                                            if ($sp->lev1 == '5') {
                                                $biaya += $sp->saldo->debit - $sp->saldo->kredit;
                                            } else {
                                                $pendapatan += $sp->saldo->kredit - $sp->saldo->debit;
                                            }
                                        }

                                        $_saldo = $pendapatan - $biaya;
                                    }

                                    $saldo_akun += $_saldo;
                                @endphp
                            @endforeach

                            <tr>
                                <td align="center">{{ $lev3->kode_akun }}</td>
                                <td>{{ $lev3->nama_akun }}</td>
                                <td align="right">
                                    <b>Rp. {{ number_format($saldo_akun, 2) }}</b>
                                </td>
                            </tr>

                            @php
                                $aset += $saldo_akun;
                                if ($lev1->lev1 > '1') {
                                    $liabilitas += $saldo_akun;
                                }
                            @endphp
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Jumlah {{ $lev1->nama_akun }}</th>
                        <th class="text-end">{{ number_format($aset, 2) }}</th>
                    </tr>
                    @if ($lev1->lev1 == '3')
                        <tr>
                            <th colspan="2">Jumlah Liabilitas + Ekuitas</th>
                            <th class="text-end">{{ number_format($liabilitas, 2) }}</th>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
@endforeach
