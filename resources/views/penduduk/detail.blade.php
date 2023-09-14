@extends('layouts.base')

@section('content')
    <div class="nav-wrapper position-relative end-0">
        <ul class="nav nav-pills nav-fill p-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#ProfilPenduduk" role="tab"
                    aria-controls="ProfilPenduduk" aria-selected="true">
                    <span class="material-icons align-middle mb-1">
                        group
                    </span>
                    Profil Penduduk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#RiwayatPiutang" role="tab"
                    aria-controls="RiwayatPiutang" aria-selected="false">
                    <span class="material-icons align-middle mb-1">
                        access_time
                    </span>
                    Riwayat Piutang
                </a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="ProfilPenduduk" role="tabpanel" aria-labelledby="ProfilPenduduk">
                <div class="card">
                    <div class="card-body">
                        <form action="/database/penduduk/{{ $penduduk->nik }}" method="post" id="Penduduk">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="_nik" id="_nik" value="{{ $penduduk->nik }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="nik">NIK</label>
                                        <input autocomplete="off" maxlength="16" type="text" name="nik"
                                            id="nik" class="form-control" value="{{ $penduduk->nik }}">
                                        <small class="text-danger" id="msg_nik"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="nama_lengkap">Nama lengkap</label>
                                        <input autocomplete="off" type="text" name="nama_lengkap" id="nama_lengkap"
                                            class="form-control" value="{{ $penduduk->namadepan }}">
                                        <small class="text-danger" id="msg_nama_lengkap"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="my-2">
                                        <label class="form-label" for="desa">Desa/Kelurahan</label>
                                        <select class="form-control" name="desa" id="desa">
                                            @foreach ($desa as $ds)
                                                <option {{ $desa_dipilih == $ds->kd_desa ? 'selected' : '' }}
                                                    value="{{ $ds->kd_desa }}">
                                                    {{ $ds->sebutan_desa->sebutan_desa }} {{ $ds->nama_desa }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="msg_desa"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="input-group input-group-static my-3">
                                                <label for="tempat_lahir">Tempat Lahir</label>
                                                <input autocomplete="off" type="text" name="tempat_lahir"
                                                    id="tempat_lahir" class="form-control"
                                                    value="{{ $penduduk->tempat_lahir }}">
                                                <small class="text-danger" id="msg_tempat_lahir"></small>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="input-group input-group-static my-3">
                                                <label for="tgl_lahir">Tgl Lahir</label>
                                                <input autocomplete="off" type="text" name="tgl_lahir" id="tgl_lahir"
                                                    class="form-control date" value="{{ $penduduk->tgl_lahir }}">
                                                <small class="text-danger" id="msg_tgl_lahir"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="my-2">
                                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option {{ $jk_dipilih == 'L' ? 'selected' : '' }} value="L">Laki Laki
                                            </option>
                                            <option {{ $jk_dipilih == 'P' ? 'selected' : '' }} value="P">Perempuan
                                            </option>
                                        </select>
                                        <small class="text-danger" id="msg_desa"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="no_telp">No. Telp</label>
                                        <input autocomplete="off" type="text" name="no_telp" id="no_telp"
                                            class="form-control" value="{{ $penduduk->hp }}">
                                        <small class="text-danger" id="msg_no_telp"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="alamat">Alamat</label>
                                        <input autocomplete="off" type="text" name="alamat" id="alamat"
                                            class="form-control" value="{{ $penduduk->alamat }}">
                                        <small class="text-danger" id="msg_alamat"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="no_kk">No. KK</label>
                                        <input autocomplete="off" type="text" name="no_kk" id="no_kk"
                                            class="form-control" value="{{ $penduduk->kk }}">
                                        <small class="text-danger" id="msg_no_kk"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="jenis_usaha">Jenis Usaha</label>
                                        <input autocomplete="off" type="text" name="jenis_usaha" id="jenis_usaha"
                                            class="form-control" value="{{ $penduduk->usaha }}">
                                        <small class="text-danger" id="msg_jenis_usaha"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="nik_penjamin">NIK Penjamin</label>
                                        <input autocomplete="off" type="text" name="nik_penjamin" id="nik_penjamin"
                                            class="form-control" value="{{ $penduduk->nik_penjamin }}">
                                        <small class="text-danger" id="msg_nik_penjamin"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label for="penjamin">Penjamin</label>
                                        <input autocomplete="off" type="text" name="penjamin" id="penjamin"
                                            class="form-control" value="{{ $penduduk->penjamin }}">
                                        <small class="text-danger" id="msg_penjamin"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="my-2">
                                        <label class="form-label" for="hubungan">Hubungan</label>
                                        <select class="form-control" name="hubungan" id="hubungan">
                                            @foreach ($hubungan as $hb)
                                                <option {{ $hubungan_dipilih == $hb->id ? 'selected' : '' }}
                                                    value="{{ $hb->id }}">
                                                    {{ $hb->kekeluargaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="msg_desa"></small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-github btn-sm float-end" id="SimpanPenduduk">
                                Simpan Penduduk
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="RiwayatPiutang" role="tabpanel" aria-labelledby="RiwayatPiutang">
                <div class="card bg-gradient-default">
                    <div class="card-body">
                        <h5 class="font-weight-normal text-info text-gradient">
                            Riwayat Piutang {{ ucwords($penduduk->namadepan) }}
                        </h5>

                        <ul class="list-group list-group-flush mt-2">
                            @foreach ($penduduk->pinjaman_anggota as $pinj)
                                @php
                                    $pinkel = $pinj->pinkel;
                                @endphp
                                <li class="list-group-item">
                                    @php
                                        if ($pinkel->status == 'P') {
                                            $tgl = $pinkel->tgl_proposal;
                                            $jumlah = $pinkel->proposal;
                                        } elseif ($pinkel->status == 'V') {
                                            $tgl = $pinkel->tgl_verifikasi;
                                            $jumlah = $pinkel->verifikasi;
                                        } elseif ($pinkel->status == 'W') {
                                            $tgl = $pinkel->tgl_cair;
                                            $jumlah = $pinkel->alokasi;
                                        } else {
                                            $tgl = $pinkel->tgl_cair;
                                            $jumlah = $pinkel->alokasi;
                                        }
                                    @endphp
                                    <blockquote data-link="/detail/{{ $pinkel->id }}"
                                        class="blockquote text-white mb-1 pointer">
                                        <p class="text-dark ms-3">
                                            <span class="badge badge-{{ $pinkel->sts->warna_status }}">
                                                Loan ID. {{ $pinkel->id }} {{ $pinj->kelompok->nama_kelompok }}
                                            </span>
                                            |
                                            <span>
                                                {{ Tanggal::tglIndo($tgl) }}
                                            </span>
                                            |
                                            <span>
                                                {{ number_format($jumlah) }}
                                            </span>
                                            |
                                            <span>
                                                {{ $pinkel->pros_jasa / $pinkel->jangka }}% @ {{ $pinkel->jangka }} Bulan
                                                --
                                                {{ $pinkel->angsuran_pokok->nama_sistem }}
                                            </span>
                                            |
                                            <span>
                                                {{ $pinkel->sts->nama_status }}
                                            </span>
                                        </p>
                                    </blockquote>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-2">
                <a href="/database/penduduk" class="btn btn-info btn-sm float-end mb-0">Kembali</a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        new Choices($('#desa')[0])
        new Choices($('#jenis_kelamin')[0])
        new Choices($('#hubungan')[0])

        $(".date").flatpickr({
            dateFormat: "d/m/Y"
        })

        $(document).on('click', '#SimpanPenduduk', function(e) {
            e.preventDefault()
            $('small').html('')

            var form = $('#Penduduk')
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    Swal.fire('Berhasil', result.msg, 'success')
                },
                error: function(result) {
                    const respons = result.responseJSON;

                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                    $.map(respons, function(res, key) {
                        $('#' + key).parent('.input-group.input-group-static').addClass(
                            'is-invalid')
                        $('#msg_' + key).html(res)
                    })
                }
            })
        })

        $(document).on('click', '.blockquote', function(e) {
            e.preventDefault()

            var link = $(this).attr('data-link')
            window.location.href = link
        })
    </script>
@endsection
