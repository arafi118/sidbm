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
        <button type="button" data-bs-toggle="modal" data-bs-target="#EditProposal" class="btn btn-success btn-sm mb-2"
            id="BtnEditProposal">Edit Proposal</button>
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
                <button type="button" id="SimpanEditProposal" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cetak Dokumen Proposal --}}
<div class="modal fade" id="CetakDokumenProposal" tabindex="-1" aria-labelledby="CetakDokumenProposalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="CetakDokumenProposalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm">Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cetak Tambah Pemanfaat --}}
<div class="modal fade" id="TambahPemanfaat" tabindex="-1" aria-labelledby="TambahPemanfaatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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

                <div id="LayoutTambahPemanfaat"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="SimpanPemanfaat" disabled class="btn btn-primary btn-sm">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<form action="/perguliran/{{ $perguliran->id }}" method="post" id="FormDeleteProposal">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
<script>
    var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })

    $.get('/perguliran/{{ $perguliran->id }}', function (result) {
        $('#layout').html(result)
    })

    $('#BtnEditProposal').click(function (e) {
        e.preventDefault()

        $.get('/perguliran/{{ $perguliran->id }}/edit', function (result) {
            $('#LayoutEditProposal').html(result)
        })
    })

    $('#HapusProposal').click(function (e) {
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
                    success: function (result) {
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
        source: function (query, process) {
            var states = [];
            return $.get('/pinjaman_anggota/cari_pemanfaat', {
                loan_id: '{{ $perguliran->id }}',
                query: query
            }, function (result) {
                var resultList = result.map(function (item) {
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
        afterSelect: function (item) {
            $.ajax({
                url: '/pinjaman_anggota/register/' + item.id_pinkel,
                type: 'get',
                data: item,
                success: function (result) {
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

    $(document).on('click', '#BtnTambahPemanfaat', function (e) {
        e.preventDefault()
        $('small').html('')

        $('#cariNik').val('')
        $('#alokasi_pengajuan').val('')

        $('#LayoutTambahPemanfaat').html('')
    })

    $(document).on('click', '#SimpanEditProposal', function (e) {
        e.preventDefault()
        $('small').html('')

        var form = $('#FormEditProposal')
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                    $.get('/perguliran/{{ $perguliran->id }}', function (result) {
                        $('#layout').html(result)

                        $('#EditProposal').modal('toggle')
                    })
                })
            },
            error: function (result) {
                const respons = result.responseJSON;

                Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                $.map(respons, function (res, key) {
                    $('#' + key).parent('.input-group.input-group-static')
                        .addClass(
                            'is-invalid')
                    $('#FormEditProposal #msg_' + key).html(res)
                })
            }
        })
    })

    $(document).on('click', '#SimpanPemanfaat', function (e) {
        e.preventDefault()
        $('small').html('')

        var form = $('#FormTambahPemanfaat')
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (result) {
                Swal.fire('Berhasil', result.msg, 'success').then(() => {
                    $.get('/perguliran/{{ $perguliran->id }}', function (result) {
                        $('#layout').html(result)
                    })

                    $('#TambahPemanfaat').modal('toggle')
                })
            },
            error: function (result) {
                const respons = result.responseJSON;

                Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                $.map(respons, function (res, key) {
                    $('#' + key).parent('.input-group.input-group-static').addClass(
                        'is-invalid')
                    $('#FormTambahPemanfaat #msg_' + key).html(res)
                })
            }
        })
    })

    $(document).on('click', '.HapusPinjamanAnggota', function (e) {
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
                    success: function (result) {
                        if (result.hapus) {
                            Swal.fire('Berhasil', result.msg, 'success').then(() => {
                                $.get('/perguliran/{{ $perguliran->id }}',
                                    function (result) {
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

    $(".money").maskMoney();

</script>
@endsection
