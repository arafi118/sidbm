@php
    $today = new DateTime();
    $tgl_lahir = new DateTime($pinj->anggota->tgl_lahir);
    $umur = $today->diff($tgl_lahir);

    $kom_pokok = 0;
    $kom_jasa = 0;

    if ($pinj->kom_pokok) {
        $angsuran_pokok = json_decode($pinj->kom_pokok, true);
        foreach ($angsuran_pokok as $pokok => $jumlah) {
            $kom_pokok += $jumlah;
        }
    }

    if ($pinj->kom_jasa) {
        $angsuran_jasa = json_decode($pinj->kom_jasa, true);
        foreach ($angsuran_jasa as $jasa => $jumlah) {
            $kom_jasa += $jumlah;
        }
    }
@endphp

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
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
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 my-3">
                        <div class="input-group input-group-static">
                            <label for="alokasi_anggota">Alokasi Rp.</label>
                            <input type="text" id="alokasi_anggota" name="alokasi_anggota" class="form-control"
                                readonly value="{{ number_format($pinj->alokasi, 2) }}">
                        </div>
                    </div>
                    <div class="col-6 my-3">
                        <div class="input-group input-group-static">
                            <label for="kom_pokok">Kom. Angsuran Pokok</label>
                            <input type="text" id="kom_pokok" name="kom_pokok" class="form-control" readonly
                                value="{{ number_format($kom_pokok, 2) }}">
                        </div>
                    </div>
                    <div class="col-6 my-3">
                        <div class="input-group input-group-static">
                            <label for="kom_jasa">Kom. Angsuran Jasa</label>
                            <input type="text" id="kom_jasa" name="kom_jasa" class="form-control" readonly
                                value="{{ number_format($kom_jasa, 2) }}">
                        </div>
                    </div>

                    @if (in_array('tahapan_perguliran.aktif.penghapusan_pinjaman_anggota', Session::get('tombol')))
                        <div class="col-6 d-grid">
                            <button type="button" data-id="{{ $pinj->id }}" id="Penghapusan"
                                class="mb-0 btn btn-sm btn-danger">
                                Penghapusan Pinjaman
                            </button>
                        </div>
                    @endif
                    @if (in_array('tahapan_perguliran.aktif.pelunasan_pinjaman_anggota', Session::get('tombol')))
                        <div class="col-6 d-grid">
                            <button type="button" id="Pelunasan" class="mb-0 btn btn-sm btn-github">
                                Pelunasan Pinjaman
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form action="/lunaskan_pemanfaat/{{ $pinj->id }}" method="post" id="formPelunasanPemanfaat">
    @csrf

    <input type="hidden" name="id_pinkel" id="id_pinkel" value="{{ $pinj->id_pinkel }}">
</form>
