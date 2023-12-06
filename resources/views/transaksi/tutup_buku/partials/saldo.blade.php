<div class="card-body p-3">
    <ul class="list-group">
        @foreach ($akun1 as $lev1)
            @foreach ($lev1->akun2 as $lev2)
                @foreach ($lev2->akun3 as $lev3)
                    @php
                        $saldo_akun = 0;
                    @endphp
                    @foreach ($lev3->rek as $rek)
                        @php
                            $_saldo = $keuangan->Saldo($tgl_kondisi, $rek->kode_akun);
                            $saldo_akun += $_saldo;
                        @endphp
                    @endforeach

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <b>{{ $lev3->kode_akun }}.</b> {{ $lev3->nama_akun }}
                        </span>
                        <span>
                            <b>Rp. {{ number_format($saldo_akun, 2) }}</b>
                        </span>
                    </li>
                @endforeach
            @endforeach
        @endforeach
    </ul>
</div>
