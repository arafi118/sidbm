@php
    use App\Utils\Tanggal;
    $waktu = date('H:i');
    $tempat = '';
    
    $sum_pokok = 0;
    if ($real) {
        $sum_pokok = $real->sum_pokok;
    }
@endphp

@extends('layouts.base')

@section('content')
    <div class="card mb-3">
        <div class="card-body p-3">
            <h5 class="mb-1">
                Kelompok {{ $perguliran->kelompok->nama_kelompok }} Loan ID. {{ $perguliran->id }}
                ({{ $perguliran->jpp->nama_jpp }})
            </h5>
            <p class="mb-0">
                <span
                    class="badge badge-{{ $perguliran->sts->warna_status }}">{{ $perguliran->kelompok->kd_kelompok }}</span>
                <span
                    class="badge badge-{{ $perguliran->sts->warna_status }}">{{ $perguliran->kelompok->alamat_kelompok }}</span>
                <span class="badge badge-{{ $perguliran->sts->warna_status }}">
                    {{ $perguliran->kelompok->d->sebutan_desa->sebutan_desa }}
                    {{ $perguliran->kelompok->d->nama_desa }}
                </span>
            </p>
        </div>
    </div>

    @if ($perguliran->status == 'P')
        <div class="card mb-3">
            <div class="card-body p-2 pb-0">
                <button type="button" class="btn btn-success btn-sm mb-2" id="BtnEditProposal">Edit Proposal</button>
                <button type="button" id="HapusProposal" class="btn btn-danger btn-sm mb-2">Hapus Proposal</button>
            </div>
        </div>
    @endif

    <div id="layout"></div>

    <div class="card mt-3">
        <div class="card-body p-2">
            <a href="/perguliran" class="btn btn-info float-end btn-sm mb-0">Kembali</a>
        </div>
    </div>

    {{-- Modal Edit Proposal --}}
    <div class="modal fade" id="EditProposal" tabindex="-1" aria-labelledby="EditProposalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="EditProposalLabel">
                        Edit Proposal Kelompok {{ $perguliran->kelompok->nama_kelompok }} Loan ID. {{ $perguliran->id }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="LayoutEditProposal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="SimpanEditProposal" class="btn btn-github btn-sm">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cetak Dokumen Proposal --}}
    <div class="modal fade" id="CetakDokumenProposal" tabindex="-1" aria-labelledby="CetakDokumenProposalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="CetakDokumenProposalLabel">Cetak Dokumen Proposal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="tglProposal">Tanggal Proposal</label>
                                <input autocomplete="off" type="text" name="tglProposal" id="tglProposal"
                                    class="form-control" readonly
                                    value="{{ Tanggal::tglIndo($perguliran->tgl_proposal) }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="tglVerifikasi">Tanggal Verifikasi</label>
                                <input autocomplete="off" type="text" name="tglVerifikasi" id="tglVerifikasi"
                                    class="form-control" readonly
                                    value="{{ Tanggal::tglIndo($perguliran->tgl_verifikasi) }}">
                            </div>
                        </div>
                    </div>

                    <form action="/perguliran/dokumen?status=P" target="_blank" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $perguliran->id }}">
                        <div class="row">
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="coverProposal">
                                    1. Cover
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="check">
                                    2. Check List
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="suratPengajuanPinjaman">
                                    3. Surat Pengajuan Pinjaman
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="suratRekomendasi">
                                    4. Surat Rekomendasi Kredit
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="profilKelompok">
                                    5. Profil Kelompok
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="susunanPengurus">
                                    6. Susunan Pengurus
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="anggotaKelompok">
                                    7. Daftar Anggota Kelompok
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="daftarPemanfaat">
                                    8. Daftar Pemanfaat
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="tanggungRenteng">
                                    9. Pernyataan Tanggung Renteng
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="fotoCopyKTP">
                                    10. FC KTP Pemanfaat & Penjamin
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="pernyataanPeminjam">
                                    11. Surat Pernyataan Peminjam
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="baMusyawarahDesa">
                                    12. BA Musyawarah Kelompok
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="formVerifikasi">
                                    13. Form Verifikasi
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="daftarHadirVerifikasi">
                                    14. Daftar Hadir Verifikasi
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="rencanaAngsuran">
                                    15. Rencana Angsuran
                                </button>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="iptw">
                                    16. Penerima IPTW
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $readonly = 'readonly';
        if ($perguliran->status == 'W') {
            $readonly = '';
        }
        
        $wt_cair = explode('_', $perguliran->wt_cair);
        if (count($wt_cair) == 1) {
            $waktu = $wt_cair[0];
        }
        
        if (count($wt_cair) == 2) {
            $waktu = $wt_cair[0];
            $tempat = $wt_cair[1];
        }
    @endphp

    {{-- Modal Cetak Dokumen Pencairan --}}
    <div class="modal fade" id="CetakDokumenPencairan" tabindex="-1" aria-labelledby="CetakDokumenPencairanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="CetakDokumenPencairanLabel">Cetak Dokumen Proposal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/perguliran/simpan_data/{{ $perguliran->id }}" method="post" id="simpanData">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="spk_no">Nomor SPK</label>
                                    <input autocomplete="off" type="text" name="spk_no" id="spk_no"
                                        class="form-control save" {{ $readonly }} value="{{ $perguliran->spk_no }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="tempat">Tempat</label>
                                    <input autocomplete="off" type="text" name="tempat" id="tempat"
                                        class="form-control save" {{ $readonly }} value="{{ $tempat }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="tgl_cair">Tanggal Cair</label>
                                    <input autocomplete="off" type="text" name="tgl_cair" id="tgl_cair"
                                        class="form-control date save" {{ $readonly }}
                                        value="{{ Tanggal::tglIndo($perguliran->tgl_cair) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="waktu">Waktu</label>
                                    <input autocomplete="off" type="text" name="waktu" id="waktu"
                                        class="form-control save" {{ $readonly }} value="{{ $waktu }}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <form action="/perguliran/dokumen?status=A" target="_blank" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $perguliran->id }}">
                        <div class="row">
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="coverPencairan">
                                    1. Cover
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="spk">
                                    2. Surat Perjanjian Kredit
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="suratKelayakan">
                                    3. Surat Kelayakan
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="suratKuasa">
                                    4. Surat Kuasa
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="BaPencairan">
                                    5. Berita Acara Pencairan
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="daftarHadirPencairan">
                                    6. Daftar Hadir Pencairan
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="tandaTerima">
                                    7. Tanda Terima
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="kartuAngsuran">
                                    8. Kartu Angsuran
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="rencanaAngsuran">
                                    9. Rencana Angsuran
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="pemberitahuanDesa">
                                    10. Pemberitahuan Ke Desa
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="iptw">
                                    11. Penerima IPTW
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="tanggungRentengKematian">
                                    12. Tanggung Renteng Kematian
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="pernyataanTanggungRenteng">
                                    13. Pernyataan Tanggung Renteng
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="kuitansi">
                                    14. Kuitansi
                                </button>
                            </div>
                            <div class="col-md-4 d-grid">
                                <button class="btn btn-slack btn-sm text-start" type="submit" name="report"
                                    value="suratTagihan">
                                    15. Surat Tagihan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cetak Tambah Pemanfaat --}}
    <div class="modal fade" id="TambahPemanfaat" tabindex="-1" aria-labelledby="TambahPemanfaatLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="TambahPemanfaatLabel">
                        Tambah Calon Pemanfaat
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/pinjaman_anggota" method="post" id="FormTambahPemanfaat">
                        @csrf

                        <input type="hidden" name="id_pinkel" id="id_pinkel" value="{{ $perguliran->id }}">
                        <input type="hidden" name="nia_pemanfaat" id="nia_pemanfaat">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="input-group input-group-static">
                                    <input type="text" id="cariNik" name="cariNik" class="form-control"
                                        placeholder="Ketikkan NIK atau Nama" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="input-group input-group-static">
                                    <input type="text" id="alokasi_pengajuan" disabled name="alokasi_pengajuan"
                                        class="form-control money" placeholder="Alokasi Pengajuan">
                                </div>
                                <small class="text-danger" id="msg_alokasi_pengajuan"></small>
                            </div>
                        </div>
                    </form>

                    <div class="card border">
                        <div class="card-body pt-3 pb-0 ps-3 pe-3">
                            <div id="LayoutTambahPemanfaat"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="SimpanPemanfaat" disabled class="btn btn-github btn-sm">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Rescedule Pinjaman --}}
    @if ($perguliran->status == 'A')
        <div class="modal fade" id="Rescedule" tabindex="-1" aria-labelledby="ResceduleLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ResceduleLabel">
                            Resceduling <span class="badge badge-info">Loan ID. {{ $perguliran->id }}</span>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-justify">
                            Fitur ini dapat Anda gunakan jika Anda akan menjadwal ulang (<b>Resceduling</b>) Pinjaman.
                            Dengan klik tombol <b>Simpan Perubahan</b> maka pinjaman ini akan berstatus R, dan akan membuat
                            pinjaman baru dengan Alokasi sebesar saldo yang ada, yaitu <b>Rp.
                                {{ number_format($perguliran->alokasi - $sum_pokok) }}</b> ;
                        </div>

                        <form action="/perguliran/rescedule" method="post" id="formRescedule">
                            @csrf

                            <input type="hidden" name="id" id="id" value="{{ $perguliran->id }}">
                            <input type="hidden" name="_pengajuan" id="_pengajuan"
                                value="{{ $perguliran->alokasi - $sum_pokok }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-static my-3">
                                        <label for="tgl_resceduling">Tgl Resceduling</label>
                                        <input autocomplete="off" type="text" name="tgl_resceduling"
                                            id="tgl_resceduling" class="form-control date" value="{{ date('d/m/Y') }}">
                                        <small class="text-danger" id="msg_tgl_resceduling"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static my-3">
                                        <label for="pengajuan">Pengajuan Rp.</label>
                                        <input autocomplete="off" type="text" name="pengajuan" id="pengajuan"
                                            class="form-control money" disabled
                                            value="{{ number_format($perguliran->alokasi - $sum_pokok, 2) }}">
                                        <small class="text-danger" id="msg_pengajuan"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label class="form-label" for="sistem_angsuran_pokok">Sistem Angs. Pokok</label>
                                        <select class="form-control" name="sistem_angsuran_pokok"
                                            id="sistem_angsuran_pokok">
                                            @foreach ($sistem_angsuran as $sa)
                                                <option {{ $perguliran->sistem_angsuran == $sa->id ? 'selected' : '' }}
                                                    value="{{ $sa->id }}">
                                                    {{ $sa->nama_sistem }} ({{ $sa->deskripsi_sistem }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="msg_sistem_angsuran_pokok"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label class="form-label" for="sistem_angsuran_jasa">Sistem Angs. Jasa</label>
                                        <select class="form-control" name="sistem_angsuran_jasa"
                                            id="sistem_angsuran_jasa">
                                            @foreach ($sistem_angsuran as $sa)
                                                <option {{ $perguliran->sa_jasa == $sa->id ? 'selected' : '' }}
                                                    value="{{ $sa->id }}">
                                                    {{ $sa->nama_sistem }} ({{ $sa->deskripsi_sistem }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="msg_sistem_angsuran_jasa"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static my-3">
                                        <label for="jangka">Jangka</label>
                                        <input autocomplete="off" type="number" name="jangka" id="jangka"
                                            class="form-control" value="{{ $perguliran->jangka }}">
                                        <small class="text-danger" id="msg_jangka"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static my-3">
                                        <label for="pros_jasa">Prosentase Jasa (%)</label>
                                        <input autocomplete="off" type="number" name="pros_jasa" id="pros_jasa"
                                            class="form-control" value="{{ $perguliran->pros_jasa }}">
                                        <small class="text-danger" id="msg_pros_jasa"></small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" id="SimpanRescedule" class="btn btn-github btn-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="/perguliran/{{ $perguliran->id }}" method="post" id="FormDeleteProposal">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('script')
    <script>
        new Choices($('#sistem_angsuran_pokok')[0])
        new Choices($('#sistem_angsuran_jasa')[0])

        $(".date").flatpickr({
            dateFormat: "d/m/Y"
        })

        var formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })

        $.get('/perguliran/{{ $perguliran->id }}', function(result) {
            $('#layout').html(result)
        })

        $('#BtnEditProposal').click(function(e) {
            e.preventDefault()

            $.get('/perguliran/{{ $perguliran->id }}/edit', function(result) {
                $('#LayoutEditProposal').html(result)
                $('#EditProposal').modal('show')
            })
        })

        $('#HapusProposal').click(function(e) {
            e.preventDefault()

            Swal.fire({
                title: 'Hapus Proposal Ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('#FormDeleteProposal')
                    $.ajax({
                        type: 'post',
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(result) {
                            if (result.hapus) {
                                Swal.fire('Berhasil!', result.msg, 'success').then(() => {
                                    window.location.href = '/perguliran'
                                })
                            } else {
                                Swal.fire('Peringatan', result.msg, 'warning')
                            }
                        }
                    })
                }
            })
        })

        $('#cariNik').typeahead({
            source: function(query, process) {
                var states = [];
                return $.get('/pinjaman_anggota/cari_pemanfaat', {
                    loan_id: '{{ $perguliran->id }}',
                    query: query
                }, function(result) {
                    var resultList = result.map(function(item) {
                        states.push({
                            "id": item.id,
                            "name": item.namadepan + ' [' + item.nik + ']',
                            "value": item.nik,
                            "id_pinkel": '{{ $perguliran->id }}'
                        });
                    });

                    return process(states);
                })
            },
            afterSelect: function(item) {
                $.ajax({
                    url: '/pinjaman_anggota/register/' + item.id_pinkel,
                    type: 'get',
                    data: item,
                    success: function(result) {
                        if (result.enable_alokasi) {
                            $('#alokasi_pengajuan').removeAttr('disabled')
                            $('#SimpanPemanfaat').removeAttr('disabled')
                        } else {
                            $('#alokasi_pengajuan').attr('disabled', true)
                            $('#SimpanPemanfaat').attr('disabled', true)
                        }

                        $('#nia_pemanfaat').val(result.nia)
                        $('#LayoutTambahPemanfaat').html(result.html)
                    }
                });
            }
        });

        $(document).on('click', '#BtnTambahPemanfaat', function(e) {
            e.preventDefault()
            $('small').html('')

            $('#cariNik').val('')
            $('#alokasi_pengajuan').val('')

            $('#LayoutTambahPemanfaat').html('')
        })

        $(document).on('click', '#SimpanEditProposal', function(e) {
            e.preventDefault()
            $('small').html('')

            var form = $('#FormEditProposal')
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    Swal.fire('Berhasil', result.msg, 'success').then(() => {
                        $.get('/perguliran/{{ $perguliran->id }}', function(result) {
                            $('#layout').html(result)

                            $('#EditProposal').modal('toggle')
                        })
                    })
                },
                error: function(result) {
                    const respons = result.responseJSON;

                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                    $.map(respons, function(res, key) {
                        $('#' + key).parent('.input-group.input-group-static')
                            .addClass(
                                'is-invalid')
                        $('#FormEditProposal #msg_' + key).html(res)
                    })
                }
            })
        })

        $(document).on('click', '#SimpanPemanfaat', function(e) {
            e.preventDefault()
            $('small').html('')

            var form = $('#FormTambahPemanfaat')
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    Swal.fire('Berhasil', result.msg, 'success').then(() => {
                        $.get('/perguliran/{{ $perguliran->id }}', function(result) {
                            $('#layout').html(result)
                        })

                        $('#TambahPemanfaat').modal('toggle')
                    })
                },
                error: function(result) {
                    const respons = result.responseJSON;

                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                    $.map(respons, function(res, key) {
                        $('#' + key).parent('.input-group.input-group-static').addClass(
                            'is-invalid')
                        $('#FormTambahPemanfaat #msg_' + key).html(res)
                    })
                }
            })
        })

        $(document).on('click', '.HapusPinjamanAnggota', function(e) {
            e.preventDefault()

            var id = $(this).attr('id')
            Swal.fire({
                title: 'Hapus Pemanfaat Ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'get',
                        url: '/hapus_pemanfaat/' + id,
                        data: {
                            'id': id
                        },
                        success: function(result) {
                            if (result.hapus) {
                                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                                    $.get('/perguliran/{{ $perguliran->id }}',
                                        function(result) {
                                            $('#layout').html(result)
                                        })
                                })
                            } else {
                                Swal.fire('Berhasil', result.msg, 'warning')
                            }
                        }
                    })
                }
            })
        })

        $(document).on('change', '.save', function() {
            var form = $('#simpanData')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('[name=tgl_cair]').val(result.tgl_cair)
                        Swal.fire('Berhasil', result.msg, 'success')
                    }
                }
            })
        })

        $(document).on('click', '#kembaliProposal', function() {
            Swal.fire({
                title: 'Peringatan',
                text: 'Anda yakin ingin mengembalikan pinjaman menjadi P (Pengajuan/Proposal)?',
                showCancelButton: true,
                confirmButtonText: 'Kembalikan',
                cancelButtonText: 'Batal',
                icon: 'warning'
            }).then((result) => {
                var form = $('#formKembaliProposal')
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(result) {
                        if (result.success) {
                            Swal.fire('Berhasil', result.msg, 'success').then(() => {
                                window.location.href = '/detail/' + result.id_pinkel
                            })
                        }
                    }
                })

            })
        })

        $(document).on('click', '#SimpanRescedule', async function(e) {
            e.preventDefault()
            $('#Rescedule').modal('hide')

            const {
                value: spk
            } = await Swal.fire({
                title: 'Masukkan Nomor SPK baru',
                input: 'text',
                inputLabel: 'Spk No.',
                confirmButtonText: 'Simpan Perubahan'
            })

            if (spk) {
                var form = $('#formRescedule')
                $.ajax({
                    type: 'POST',
                    url: form.attr('action') + '?spk=' + spk,
                    data: form.serialize(),
                    success: function(result) {
                        if (result.success) {
                            var id = result.id
                            $.get('/perguliran/generate/' + result.id + '?status=' + result.status +
                                '&save',
                                function(result) {
                                    if (result.success) {
                                        Swal.fire('Berhasil', result.msg, 'success').then(
                                            () => {
                                                window.location.href = '/detail/' + id
                                            })
                                    }
                                })
                        }
                    }
                })
            }

        })

        $(".money").maskMoney();
    </script>
@endsection
