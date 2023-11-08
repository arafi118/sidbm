@php
    $today = new DateTime();
    $tgl_lahir = new DateTime($pinj->anggota->tgl_lahir);
    $umur = $today->diff($tgl_lahir);
@endphp

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="/assets/img/avatar/female.jpg" class="rounded-circle border" style="width: 150px;"
                    alt="Avatar" />

                <h5 class="mb-2">
                    <b>{{ ucwords(strtolower($pinj->anggota->namadepan)) }}</b>
                </h5>

                <div class="text-muted">
                    {{ $pinj->anggota->nik }}
                </div>

                @if ($umur->m == 0)
                    <div class="text-muted">{{ $umur->y . ' th' }}</div>
                @else
                    <div class="text-muted">{{ $umur->y . ' th ' . $umur->m . ' bln' }}</div>
                @endif

            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 my-3">
                        <div class="input-group input-group-static">
                            <label for="proposal_anggota">Pengajuan Rp.</label>
                            <input type="text" id="proposal_anggota" name="proposal_anggota" class="form-control"
                                readonly value="{{ number_format($pinj->proposal, 2) }}">
                        </div>
                    </div>
                    <div class="col-4 my-3">
                        <div class="input-group input-group-static">
                            <label for="verifikasi_anggota">Verifikasi Rp.</label>
                            <input type="text" id="verifikasi_anggota" name="verifikasi_anggota" class="form-control"
                                readonly value="{{ number_format($pinj->verifikasi, 2) }}">
                        </div>
                    </div>
                    <div class="col-4 my-3">
                        <div class="input-group input-group-static">
                            <label for="alokasi_anggota">Alokasi Rp.</label>
                            <input type="text" id="alokasi_anggota" name="alokasi_anggota" class="form-control"
                                readonly value="{{ number_format($pinj->alokasi, 2) }}">
                        </div>
                    </div>

                    <div class="col-6 d-grid">
                        <button type="button" id="Penghapusan" disabled class="mb-0 btn btn-sm btn-danger">
                            Penghapusan Pinjaman
                        </button>
                    </div>
                    <div class="col-6 d-grid">
                        <button type="button" id="Pelunasan" class="mb-0 btn btn-sm btn-github">
                            Pelunasan Pinjaman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="/lunaskan_pemanfaat/{{ $pinj->id }}" method="post" id="formPelunasanPemanfaat">
    @csrf

    <input type="hidden" name="id_pinkel" id="id_pinkel" value="{{ $pinj->id_pinkel }}">
</form>
