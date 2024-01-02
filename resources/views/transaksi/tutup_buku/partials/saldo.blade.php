@php
    $aset = 0;
    $liabilitas = 0;
    $laba_rugi = 0;
@endphp

<div class="alert alert-warning text-white">
    <b>Saldo Tutup Buku</b> tidak sama dengan <b>Saldo Neraca?</b> Klik
    <a href="#" id="SimpanSaldo" class="alert-link text-white">Disini</a>.
</div>

@foreach ($akun1 as $lev1)
    @php
        $total_saldo = 0;
    @endphp

    <div class="card mb-3">
        <div class="card-body p-3 pb-0">
            <div class="table-responsive">
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
                                        $awal_debit = 0;
                                        $saldo_debit = 0;
                                        $awal_kredit = 0;
                                        $saldo_kredit = 0;
                                        foreach ($rek->kom_saldo as $kom_saldo) {
                                            if ($kom_saldo->bulan == 0) {
                                                $awal_debit += floatval($kom_saldo->debit);
                                                $awal_kredit += floatval($kom_saldo->kredit);
                                            } else {
                                                $saldo_debit += floatval($kom_saldo->debit);
                                                $saldo_kredit += floatval($kom_saldo->kredit);
                                            }
                                        }

                                        if ($lev1->lev1 <= 1) {
                                            $saldo_awal = $awal_debit - $awal_kredit;
                                            $_saldo = $saldo_awal + ($saldo_debit - $saldo_kredit);
                                        } else {
                                            $saldo_awal = $awal_kredit - $awal_debit;
                                            $_saldo = $saldo_awal + ($saldo_kredit - $saldo_debit);
                                        }

                                        if ($rek->kode_akun == '3.2.02.01') {
                                            $_saldo = $surplus;
                                            $laba_rugi = $surplus;
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
                                    $total_saldo += $saldo_akun;
                                    if ($lev1->lev1 > '1') {
                                        $liabilitas += $saldo_akun;
                                    } else {
                                        $aset += $saldo_akun;
                                    }
                                @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Jumlah {{ $lev1->nama_akun }}</th>
                            <th class="text-end">{{ number_format($total_saldo, 2) }}</th>
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
    </div>
@endforeach

<form action="/transaksi/tutup_buku" method="post" id="FormSimpanTutupBuku">
    @csrf

    <input type="hidden" name="tahun" id="tahun" value="{{ $tahun }}">
    <input type="hidden" name="jumlah_riwayat" id="jumlah_riwayat" value="{{ $jumlah_riwayat }}">
    <input type="hidden" name="total_riwayat" id="total_riwayat" value="{{ $total_riwayat }}">
    <input type="hidden" name="pembagian_laba" id="pembagian_laba" value="false">

    <div class="card">
        <div class="card-body p-2">
            <button type="submit" id="SimpanTutupBuku" class="btn btn-info float-end btn-sm mb-0">
                Lanjutkan Tutup Buku
            </button>
        </div>
    </div>
</form>
