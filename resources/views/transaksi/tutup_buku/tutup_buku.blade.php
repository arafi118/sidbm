@php
    use App\Utils\Tanggal;

    $title_form = [
        1 => 'Kegiatan sosial kemasyarakatan dan bantuan RTM',
        2 => 'Pengembangan kapasitas kelompok SPP/UEP',
        3 => 'Pelatihan masyarakat, dan kelompok pemanfaat umum',
        4 => 'Penambahan Modal DBM',
        5 => 'Penambahan Investasi Usaha',
        6 => 'Pendirian Unit Usaha',
    ];
@endphp

@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($success)
                <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md">
                            thumb_up_off_alt
                        </span>
                    </span>
                    <span class="alert-text">
                        <strong>Tutup Buku Tahun {{ $tahun }}</strong> berhasil.
                        Anda dapat melanjutkan proses pembagian laba di lain hari,
                        klik <a href="/transaksi/tutup_buku" class="fw-bold text-white">Disini</a>
                        untuk kembali.
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <h4 class="font-weight-normal mt-3">
                <div class="row">
                    <span class="col-sm-6">Laba/Rugi Tahun {{ Tanggal::tahun($tgl_kondisi) }}</span>
                    <span class="col-sm-6 text-end">Rp. {{ number_format($surplus, 2) }}</span>
                </div>
            </h4>

            <form action="/transaksi/simpan_laba" method="post" id="SimpanAlokasiLaba">
                @csrf

                <input type="hidden" name="tgl_kondisi" id="tgl_kondisi" value="{{ $tgl_kondisi }}">
                <div class="row">
                    <input type="hidden" name="surplus" id="surplus" value="{{ $surplus }}">
                    <div class="card">
                        <div class="card-body p-3">
                            <h4 class="font-weight-normal">
                                Alokasi Laba Usaha
                            </h4>

                            @foreach ($rekening as $rek)
                                <div class="table-responsive mb-3">
                                    <table class="table table-striped midle">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th width="50%">
                                                    <span class="text-sm">
                                                        {{ str_replace('Utang', '', $rek->nama_akun) }}
                                                    </span>
                                                </th>
                                                <th width="50%">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-sm">Jumlah</span>
                                                        <span class="text-sm">
                                                            Rp. <span
                                                                data-id="total{{ str_replace(' ', '_', str_replace('utang', '', strtolower($rek->nama_akun))) }}">0,00</span>
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($rek->kode_akun == '2.1.04.01')
                                                @foreach ($kec->saldo as $saldo)
                                                    @if (substr($saldo->id, -1) <= 3)
                                                        <tr>
                                                            <td>{{ $title_form[substr($saldo->id, -1)] }}</td>
                                                            <td>
                                                                <div class="input-group input-group-outline my-0">
                                                                    <input type="text"
                                                                        name="masyarakat[{{ substr($saldo->id, -1) }}]"
                                                                        id="{{ substr($saldo->id, -1) }}"
                                                                        class="form-control nominal bagian-masyarakat form-control-sm text-end">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if ($rek->kode_akun == '2.1.04.02')
                                                @foreach ($desa as $d)
                                                    <tr>
                                                        <td>
                                                            Bagian {{ $d->sebutan_desa->sebutan_desa }}
                                                            {{ $d->nama_desa }}
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-outline my-0">
                                                                <input type="text" name="desa[{{ $d->kd_desa }}]"
                                                                    id="{{ $d->kd_desa }}"
                                                                    class="form-control form-control-sm bagian-desa nominal text-end">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            @if ($rek->kode_akun == '2.1.04.03')
                                                <tr>
                                                    <td>
                                                        {{ str_replace('Utang', '', $rek->nama_akun) }}
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-outline my-0">
                                                            <input type="text"
                                                                name="total{{ str_replace(' ', '_', str_replace('utang', '', strtolower($rek->nama_akun))) }}"
                                                                id="total{{ str_replace(' ', '_', str_replace('utang', '', strtolower($rek->nama_akun))) }}"
                                                                class="form-control form-control-sm total nominal text-end">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                <input type="hidden" class="total"
                                                    name="total{{ str_replace(' ', '_', str_replace('utang', '', strtolower($rek->nama_akun))) }}"
                                                    id="total{{ str_replace(' ', '_', str_replace('utang', '', strtolower($rek->nama_akun))) }}">
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            <div class="table-responsive mb-3">
                                <table class="table table-striped midle">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th width="50%">
                                                <span class="text-sm">Laba Ditahan</span>
                                            </th>
                                            <th width="50%">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-sm">Jumlah</span>
                                                    <span class="text-sm">
                                                        Rp. <span data-id="total_laba_ditahan">
                                                            {{ number_format($surplus, 2) }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input type="hidden" name="laba_ditahan" id="laba_ditahan"
                                            class="form-control form-control-sm text-end" value="{{ $surplus }}">
                                        @foreach ($kec->saldo as $saldo)
                                            @if (substr($saldo->id, -1) > 3)
                                                @php
                                                    $value = 0;
                                                    $readonly = false;
                                                    if (substr($saldo->id, -1) == 4) {
                                                        $value = $surplus;
                                                        $readonly = true;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $title_form[substr($saldo->id, -1)] }}</td>
                                                    <td>
                                                        <div class="input-group input-group-outline my-0">
                                                            <input type="text"
                                                                name="laba_ditahan[{{ substr($saldo->id, -1) }}]"
                                                                id="{{ substr($saldo->id, -1) }}"
                                                                class="form-control {{ $readonly ? '' : 'nominal' }} laba_ditahan form-control-sm text-end"
                                                                value="{{ number_format($value, 2) }}"
                                                                {{ $readonly ? 'readonly' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" id="btnSimpanLaba" class="btn btn-github btn-sm">
                                    Simpan Alokasi Laba
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @if ($migrasi_saldo)
        <script>
            window.open('/transaksi/tutup_buku/saldo_awal/{{ $tahun }}')
        </script>
    @endif
    <script>
        $(".nominal").maskMoney({
            allowNegative: true
        });

        var formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })

        $(document).on('change', '.bagian-masyarakat', function(e) {
            var total = 0;
            $('.bagian-masyarakat').map(function() {
                var value = $(this).val()
                if (value == '') {
                    value = 0
                } else {
                    value = value.split(',').join('')
                    value = value.split('.00').join('')
                }

                value = parseFloat(value)

                total += value
            })

            $('#total_laba_bagian_masyarakat').val(formatter.format(total)).trigger('change')
            $('[data-id=total_laba_bagian_masyarakat]').html(formatter.format(total))
        })

        $(document).on('change', '.bagian-desa', function(e) {
            var total = 0;
            $('.bagian-desa').map(function() {
                var value = $(this).val()
                if (value == '') {
                    value = 0
                } else {
                    value = value.split(',').join('')
                    value = value.split('.00').join('')
                }

                value = parseFloat(value)

                total += value
            })

            $('#total_laba_bagian_desa').val(formatter.format(total)).trigger('change')
            $('[data-id=total_laba_bagian_desa]').html(formatter.format(total))
        })

        $(document).on('change', '.total', function(e) {
            var total = 0;
            $('.total').map(function() {
                var value = $(this).val()
                if (value == '') {
                    value = 0
                } else {
                    value = value.split(',').join('')
                    value = value.split('.00').join('')
                }

                value = parseFloat(value)

                total += value
            })

            var laba_penyerta_modal = '0,00'
            if ($('#total_laba_bagian_penyerta_modal').val()) {
                laba_penyerta_modal = $('#total_laba_bagian_penyerta_modal').val()
            }

            var surplus = $('#surplus').val()
            $('#laba_ditahan').val(surplus - total)
            $('[data-id=total_laba_ditahan]').html(formatter.format(surplus - total))
            $('[data-id=total_laba_bagian_penyerta_modal]').html(laba_penyerta_modal)
            $('#4').val(formatter.format(surplus - total))
        })

        $(document).on('change', '.laba_ditahan:not(#4)', function() {
            var total = 0;
            $('.laba_ditahan').map(function() {
                if ($(this).attr('id') > 4) {
                    var value = $(this).val()
                    if (value == '') {
                        value = 0
                    } else {
                        value = value.split(',').join('')
                        value = value.split('.00').join('')
                    }

                    value = parseFloat(value)
                    total += value
                }
            })

            var laba_ditahan = $('#laba_ditahan').val()
            $('#4').val(formatter.format(laba_ditahan - total))
        })

        $(document).on('click', '#btnSimpanLaba', function(e) {
            e.preventDefault()

            var form = $('#SimpanAlokasiLaba')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Swal.fire('Selamat', result.msg, 'success').then(() => {
                            window.location.href = '/transaksi/tutup_buku'
                        })
                    }
                }
            })
        })
    </script>
@endsection
