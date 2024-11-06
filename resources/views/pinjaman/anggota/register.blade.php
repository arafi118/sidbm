@php
    $today = new DateTime();
    $tgl_lahir = new DateTime($anggota->tgl_lahir);
    $umur = $today->diff($tgl_lahir);
@endphp

<div class="row">
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="/assets/img/avatar/female.jpg" class="rounded-circle border" style="width: 150px;"
                    alt="Avatar" />

                <h5 class="mb-2">
                    <b>{{ ucwords(strtolower($anggota->namadepan)) }}</b>
                </h5>

                <div class="text-muted">
                    {{ $anggota->nik }} ({{ $anggota->id }})
                </div>

                @if ($umur->m == 0)
                    <div class="text-muted">{{ $umur->y . ' th' }}</div>
                @else
                    <div class="text-muted">{{ $umur->y . ' th ' . $umur->m . ' bln' }}</div>
                @endif

            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-3">
        @if ($anggota->status == '0')
            <div class="alert alert-danger text-white" role="alert">
                <span class="text-sm">
                    Pemanfaat saat ini berada dalam status <b>blokir</b> sehingga tidak bisa mengajukan pinjaman.
                </span>
            </div>
        @else
            @if ($jumlah_pinjaman_anggota > 0)
                <div class="alert alert-danger text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan memiliki Proposal dalam Proses
                        dengan status <b>{{ $pinjaman_anggota->status }}</b> pada kelompok
                        <b>{{ $pinjaman_anggota->kelompok->nama_kelompok . ' ' . $pinjaman_anggota->kelompok->alamat_kelompok }}</b>,
                        dengan pengajuan sebesar <b>Rp. {{ number_format($pinjaman_anggota->proposal) }}</b>!
                    </span>
                </div>
            @else
                <div class="alert alert-success text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan tidak memiliki Proposal dalam Proses
                    </span>
                </div>
            @endif

            @if ($jumlah_pinjaman_anggota_a > 0)
                <div class="alert alert-warning text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan memiliki Pinjaman Aktif pada kelompok
                        <b>{{ $pinjaman_anggota_a->kelompok->nama_kelompok . ' ' . $pinjaman_anggota_a->kelompok->alamat_kelompok }}
                            - Loan ID. {{ $pinjaman_anggota_a->id_pinkel }}</b>,
                        dengan alokasi sebesar <b>Rp. {{ number_format($pinjaman_anggota_a->alokasi) }}</b>!
                    </span>
                </div>
            @else
                <div class="alert alert-success text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan tidak memiliki Pinjaman Aktif
                    </span>
                </div>
            @endif

            @if ($jumlah_data_pemanfaat > 0)
                <div class="alert alert-danger text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan memiliki Proposal dalam Proses di {{ $data_pemanfaat->kec->sebutan_kec }}
                        {{ $data_pemanfaat->kec->nama_kec }}</b>!
                    </span>
                </div>
            @else
                <div class="alert alert-success text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan tidak memiliki Proposal dalam Proses di Kecamatan Lain
                    </span>
                </div>
            @endif

            @if ($jumlah_data_pemanfaat_a > 0)
                <div class="alert alert-warning text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan memiliki Pinjaman Aktif di {{ $data_pemanfaat->kec->sebutan_kec }}
                        {{ $data_pemanfaat->kec->nama_kec }}</b>!
                    </span>
                </div>
            @else
                <div class="alert alert-success text-white" role="alert">
                    <span class="text-sm">
                        Yang bersangkutan tidak memiliki Pinjaman Aktif di Kecamatan Lain
                    </span>
                </div>
            @endif
        @endif
    </div>
</div>
