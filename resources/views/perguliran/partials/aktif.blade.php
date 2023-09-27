<div class="card mb-3">
    <div class="card-body">
        <div class="row mt-0">
            <div class="col-md-4 mb-3">
                <div class="border border-light border-2 border-radius-md p-3">
                    <h6 class="text-info text-gradient mb-0">
                        Proposal
                    </h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Tgl Pengajuan
                            <span class="badge badge-info badge-pill">
                                {{ Tanggal::tglIndo($perguliran->tgl_proposal) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Pengajuan
                            <span class="badge badge-info badge-pill">
                                {{ number_format($perguliran->proposal) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jenis Jasa
                            <span class="badge badge-info badge-pill">
                                {{ $perguliran->jasa->nama_jj }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jasa
                            <span class="badge badge-info badge-pill">
                                {{ $perguliran->pros_jasa . '% / ' . $perguliran->jangka . ' bulan' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Pokok
                            <span class="badge badge-info badge-pill">
                                {{ $perguliran->sis_pokok->nama_sistem }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Jasa
                            <span class="badge badge-info badge-pill">
                                {{ $perguliran->sis_jasa->nama_sistem }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="border border-light border-2 border-radius-md p-3">
                    <h6 class="text-danger text-gradient mb-0">
                        Verified
                    </h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Tgl Verifikasi
                            <span class="badge badge-danger badge-pill">
                                {{ Tanggal::tglIndo($perguliran->tgl_verifikasi) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Verifikasi
                            <span class="badge badge-danger badge-pill">
                                {{ number_format($perguliran->verifikasi) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jenis Jasa
                            <span class="badge badge-danger badge-pill">
                                {{ $perguliran->jasa->nama_jj }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jasa
                            <span class="badge badge-danger badge-pill">
                                {{ $perguliran->pros_jasa . '% / ' . $perguliran->jangka . ' bulan' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Pokok
                            <span class="badge badge-danger badge-pill">
                                {{ $perguliran->sis_pokok->nama_sistem }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Jasa
                            <span class="badge badge-danger badge-pill">
                                {{ $perguliran->sis_jasa->nama_sistem }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="border border-light border-2 border-radius-md p-3">
                    <h6 class="text-warning text-gradient mb-0">
                        Waiting
                    </h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Tgl Tunggu
                            <span class="badge badge-warning badge-pill">
                                {{ Tanggal::tglIndo($perguliran->tgl_tunggu) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Pendanaan
                            <span class="badge badge-warning badge-pill">
                                {{ number_format($perguliran->alokasi) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jenis Jasa
                            <span class="badge badge-warning badge-pill">
                                {{ $perguliran->jasa->nama_jj }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Jasa
                            <span class="badge badge-warning badge-pill">
                                {{ $perguliran->pros_jasa . '% / ' . $perguliran->jangka . ' bulan' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Pokok
                            <span class="badge badge-warning badge-pill">
                                {{ $perguliran->sis_pokok->nama_sistem }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-sm">
                            Angs. Jasa
                            <span class="badge badge-warning badge-pill">
                                {{ $perguliran->sis_jasa->nama_sistem }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="horizontal dark">

        <div class="table-responsive">
            <table class="table align-items-center mb-0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Pengajuan</th>
                        <th>Verifikasi</th>
                        <th>Alokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $proposal = 0;
                        $verifikasi = 0;
                        $alokasi = 0;
                    @endphp
                    @foreach ($perguliran->pinjaman_anggota as $pinjaman_anggota)
                        @php
                            $proposal += $pinjaman_anggota->proposal;
                            $verifikasi += $pinjaman_anggota->verifikasi;
                            $alokasi += $pinjaman_anggota->alokasi;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ ucwords($pinjaman_anggota->anggota->namadepan) }}
                                ({{ $pinjaman_anggota->id }})
                            </td>
                            <td>
                                {{ number_format($pinjaman_anggota->proposal, 2) }}
                            </td>
                            <td>
                                {{ number_format($pinjaman_anggota->verifikasi, 2) }}
                            </td>
                            <td>
                                {{ number_format($pinjaman_anggota->alokasi, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Jumlah</th>
                        <th>
                            {{ number_format($proposal, 2) }}
                        </th>
                        <th id="jumlah">
                            {{ number_format($verifikasi, 2) }}
                        </th>
                        <th>
                            {{ number_format($alokasi, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card card-body p-2 pb-0 mb-3">
    <form action="/perguliran/dokumen?status=A" target="_blank" method="post">
        @csrf

        <input type="hidden" name="id" value="{{ $perguliran->id }}">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="d-grid">
                    <a href="/perguliran/dokumen/kartu_angsuran/{{ $perguliran->id }}" target="_blank"
                        class="btn btn-outline-info btn-sm mb-2">Kartu Angsuran</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-info btn-sm mb-2" name="report"
                        value="rencanaAngsuran">Rencana Angsuran</button>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-info btn-sm mb-2" name="report"
                        value="rekeningKoran">Rekening Koran</button>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="d-grid">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#CetakDokumenPencairan"
                        class="btn btn-info btn-sm mb-2">Cetak Dokumen Pencairan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="card mb-3">
    <div class="card-body pb-2">
        <h5 class="mb-1">
            Riwayat Angsuran
        </h5>

        <div class="table-responsive">
            <table class="table align-items-center mb-0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tgl transaksi</th>
                        <th>Pokok</th>
                        <th>Jasa</th>
                        <th>Saldo Pokok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perguliran->real as $real)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Tanggal::tglIndo($real->tgl_transaksi) }}</td>
                            <td>{{ number_format($real->realisasi_pokok) }}</td>
                            <td>{{ number_format($real->realisasi_jasa) }}</td>
                            <td>{{ number_format($real->saldo_pokok) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="button" data-bs-toggle="modal" data-bs-target="#Rescedule"
                class="btn btn-warning btn-sm">Resceduling Pinjaman</button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#Penghapusan"
                class="btn btn-danger btn-sm ms-1">Penghapusan Pinjaman</button>
        </div>
    </div>
</div>
