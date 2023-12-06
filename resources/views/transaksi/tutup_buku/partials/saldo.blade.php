<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body p-3">
                <ul class="list-group">
                    @foreach ($rekening1 as $aset)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $aset->nama_akun }}
                            <span>
                                <b>Rp. {{ number_format($aset->saldo->debit - $aset->saldo->kredit) }}</b>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Cras justo odio
                        <span class="badge badge-primary badge-pill">14</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
