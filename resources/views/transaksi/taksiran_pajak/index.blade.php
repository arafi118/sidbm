@extends('layouts.base')

@section('content')
    <form action="/transaksi/taksiran_pajak" method="post" target="_blank">
        @csrf

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group input-group-static my-2">
                            <label for="nama_lembaga">Nama Lembaga</label>
                            <input autocomplete="off" readonly type="text" name="nama_lembaga" id="nama_lembaga"
                                class="form-control" value="{{ $kec->nama_lembaga_sort }}">
                            <small class="text-danger" id="msg_nama_lembaga"></small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group input-group-static my-2">
                            <label for="npwp">NPWP</label>
                            <input autocomplete="off" readonly type="text" name="npwp" id="npwp"
                                class="form-control" value="{{ $kec->npwp }}">
                            <small class="text-danger" id="msg_npwp"></small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group input-group-static my-2">
                            <label for="tanggal_npwp">Tanggal NPWP</label>
                            <input autocomplete="off" readonly type="text" name="tanggal_npwp" id="tanggal_npwp"
                                class="form-control" value="{{ Tanggal::tglIndo($kec->tgl_npwp) }}">
                            <small class="text-danger" id="msg_tanggal_npwp"></small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <label class="form-label" for="tahun_pajak">Tahun Pajak</label>
                            <select class="form-control" name="tahun_pajak" id="tahun_pajak">
                                <option value="">-- Tahun Pajak --</option>
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <small class="text-danger" id="msg_tahun_pajak"></small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <label class="form-label" for="masa_pajak">Masa Pajak</label>
                            <select class="form-control" name="masa_pajak" id="masa_pajak">
                                <option value="">-- Tahun Pajak --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                                <option value="-12">1-12</option>
                            </select>
                            <small class="text-danger" id="msg_masa_pajak"></small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group input-group-static my-2">
                            <label for="pph_final">PPh Final (0.5%)</label>
                            <input autocomplete="off" readonly type="text" name="pph_final" id="pph_final"
                                class="form-control">
                            <small class="text-danger" id="msg_pph_final"></small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group input-group-static my-2">
                            <label for="pph_badan">PPh Badan (11%)</label>
                            <input autocomplete="off" readonly type="text" name="pph_badan" id="pph_badan"
                                class="form-control">
                            <small class="text-danger" id="msg_pph_badan"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 midle">
                        <tbody>
                            @foreach ($akun2 as $akn2)
                                <tr class="bg-dark">
                                    <td class="text-white" colspan="2">
                                        <div class="text-uppercase">
                                            {{ $akn2->kode_akun }}. {{ $akn2->nama_akun }}
                                        </div>
                                    </td>
                                </tr>

                                @foreach ($akn2->rek as $rek)
                                    @php
                                        $saldo = $keuangan->saldoBulanIni($rek);
                                    @endphp
                                    <tr>
                                        <td class="ps-1">
                                            <div class="my-auto">
                                                <span class="text-dark d-block text-sm">{{ $rek->kode_akun }}</span>
                                                <span class="text-xs font-weight-normal">
                                                    {{ $rek->nama_akun }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div
                                                class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                <input class="form-check-input kode_akun" type="checkbox"
                                                    name="rekening[{{ $rek->kode_akun }}]" id="{{ $rek->kode_akun }}"
                                                    value="{{ $saldo }}">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr class="bg-dark">
                                <td class="text-white">
                                    <div class="text-uppercase">
                                        Total Pendapatan
                                    </div>
                                </td>
                                <td class="text-white text-end">
                                    <div id="_pendapatan">0.00</div>
                                    <input type="hidden" name="pendapatan" id="pendapatan">
                                </td>
                            </tr>
                            <tr class="bg-dark">
                                <td class="text-white">
                                    <div class="text-uppercase">
                                        Total biaya (tidak termasuk Pajak)
                                    </div>
                                </td>
                                <td class="text-white text-end">
                                    <div id="_beban">{{ number_format($beban, 2) }}</div>
                                    <input type="hidden" name="beban" id="beban" value="{{ $beban }}">
                                </td>
                            </tr>
                            <tr class="bg-dark">
                                <td class="text-white">
                                    <div class="text-uppercase">
                                        Laba Sebelum Taksiran Pajak
                                    </div>
                                </td>
                                <td class="text-white text-end">
                                    <div id="_laba">{{ number_format(0 - $beban, 2) }}</div>
                                    <input type="hidden" name="laba" id="laba" value="{{ 0 - $beban }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body p-2">
                <div class="d-flex justify-content-end gap-2">
                    @if (in_array('taksiran_pajak.cetak_taksiran_pajak_pph_0.5', Session::get('tombol')))
                        <button type="submit" name="pph" value="0.5" class="btn btn-info float-end btn-sm mb-0">
                            Cetak Taksiran PPh (0.5%)
                        </button>
                    @endif
                    @if (in_array('taksiran_pajak.cetak_taksiran_pajak_pph_11', Session::get('tombol')))
                        <button type="submit" name="pph" value="11" class="btn btn-info float-end btn-sm mb-0">
                            Cetak Taksiran PPh (11%)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        var formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })

        $(".date").flatpickr({
            dateFormat: "d/m/Y"
        })

        $(".nominal").maskMoney({
            allowNegative: true
        });

        var tahun_pajak = new Choices($('#tahun_pajak')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        var masa_pajak = new Choices($('#masa_pajak')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('change', '#tahun_pajak, #masa_pajak', function() {
            var tahun = $('#tahun_pajak').val();
            var bulan = $('#masa_pajak').val();

            $.get('/transaksi/pendapatan/' + tahun + '/' + bulan, function(result) {
                if (result.success) {
                    var pendapatan = result.pendapatan
                    var beban = result.beban

                    $('#beban').val(beban)
                    $('#_beban').html(formatter.format(beban))

                    $('input.kode_akun').map((i, val) => {
                        val.value = pendapatan[val.getAttribute('id')]
                    })

                    hitungPPh()
                }
            })
        })

        $(document).on('click', '.kode_akun', function() {
            hitungPPh()
        })

        function hitungPPh() {
            var pendapatan = 0
            var beban = parseFloat($('#beban').val())
            $('input.kode_akun').map((i, val) => {
                if (val.checked) {
                    pendapatan += parseFloat(val.value)
                }
            })

            console.log(pendapatan);
            var laba = pendapatan - beban;

            var pph1 = pendapatan * (0.5 / 100)
            var pph2 = laba * (11 / 100)

            $('#pendapatan').val(pendapatan)
            $('#laba').val(laba)

            $('#_pendapatan').html(formatter.format(pendapatan))
            $('#_laba').html(formatter.format(laba))

            $('#pph_final').val(formatter.format(pph1))
            $('#pph_badan').val(formatter.format(pph2))
        }
    </script>
@endsection
