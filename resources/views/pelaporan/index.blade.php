@php
$thn_awal = explode('-', $kec->tgl_pakai)[0];
@endphp

@extends('layouts.base')

@section('content')
<div class="card mb-3">
    <div class="card-body pb-0">
        <div class="row">
            <div class="col-md-4">
                <div class="my-2">
                    <label class="form-label" for="tahun">Tahunan</label>
                    <select class="form-control" name="tahun" id="tahun">
                        <option value="">---</option>
                        @for ($i = $thn_awal; $i <= date('Y'); $i++) <option value="{{ $i }}">
                            {{ $i }}
                            </option>
                            @endfor
                    </select>
                    <small class="text-danger" id="msg_tahun"></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="my-2">
                    <label class="form-label" for="bulan">Bulanan</label>
                    <select class="form-control" name="bulan" id="bulan">
                        <option value="">---</option>
                        <option value="01">01. JANUARI</option>
                        <option value="02">02. FEBRUARI</option>
                        <option value="03">03. MARET</option>
                        <option value="04">04. APRIL</option>
                        <option value="05">05. MEI</option>
                        <option value="06">06. JUNI</option>
                        <option value="07">07. JULI</option>
                        <option value="08">08. AGUSTUS</option>
                        <option value="09">09. SEPTEMBER</option>
                        <option value="10">10. OKTOBER</option>
                        <option value="11">11. NOVEMBER</option>
                        <option value="12">12. DESEMBER</option>
                    </select>
                    <small class="text-danger" id="msg_bulan"></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="my-2">
                    <label class="form-label" for="hari">Harian</label>
                    <select class="form-control" name="hari" id="hari">
                        <option value="">---</option>
                        @for ($j = 1; $j <= 31; $j++) @if ($j < 10) <option value="0{{ $j }}">0{{ $j }}</option>
                            @else
                            <option value="{{ $j }}">{{ $j }}</option>
                            @endif
                            @endfor
                    </select>
                    <small class="text-danger" id="msg_hari"></small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="my-2">
                    <label class="form-label" for="laporan">Nama Laporan</label>
                    <select class="form-control" name="laporan" id="laporan">
                        <option value="">---</option>
                        @foreach ($laporan as $lap)
                        <option value="{{ $lap->file }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}.
                            {{ $lap->nama_laporan }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-danger" id="msg_laporan"></small>
                </div>
            </div>
            <div class="col-md-6" id="subLaporan">
                <div class="my-2">
                    <label class="form-label" for="sub_laporan">Nama Sub Laporan</label>
                    <select class="form-control" name="sub_laporan" id="sub_laporan">
                        <option value="">---</option>
                    </select>
                    <small class="text-danger" id="msg_sub_laporan"></small>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" id="Preview" class="btn btn-sm btn-primary">Preview</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-2" id="LayoutPreview"></div>
</div>
@endsection

@section('script')
<script>
    new Choices($('#tahun')[0])
    new Choices($('#bulan')[0])
    new Choices($('#hari')[0])
    new Choices($('#laporan')[0])
    new Choices($('#sub_laporan')[0])

    $(document).on('change', '#laporan', function (e) {
        e.preventDefault()

        var file = $(this).val()
        $.get('/pelaporan/sub_laporan/' + file, function (result) {
            $('#subLaporan').html(result)
        })
    })

</script>
@endsection
