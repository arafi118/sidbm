@if ($file == 3)
<div class="my-2">
    <label class="form-label" for="sub_laporan">Nama Sub Laporan</label>
    <select class="form-control" name="sub_laporan" id="sub_laporan">
        <option value="">---</option>
        @foreach ($rekening as $rek)
        <option value="BB_{{ $rek->kode_akun }}">{{ $rek->kode_akun }}. {{ $rek->nama_akun }}</option>
        @endforeach

        @foreach ($akun as $akn)
        <option value="BB_{{ $akn->kode_akun }}">{{ $akn->kode_akun }}. {{ $akn->nama_akun }}</option>
        @endforeach
    </select>
    <small class="text-danger" id="msg_sub_laporan"></small>
</div>
@elseif ($file == 5)
<div class="my-2">
    <label class="form-label" for="sub_laporan">Nama Sub Laporan</label>
    <select class="form-control" name="sub_laporan" id="sub_laporan">
        <option value="">---</option>
        @foreach ($jenis_laporan as $jl)
        <option value="{{ $jl->file }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}. {{ $jl->nama_laporan }}
        </option>
        @endforeach
    </select>
    <small class="text-danger" id="msg_sub_laporan"></small>
</div>
@else
<div class="my-2">
    <label class="form-label" for="sub_laporan">Nama Sub Laporan</label>
    <select class="form-control" name="sub_laporan" id="sub_laporan">
        <option value="">---</option>
    </select>
    <small class="text-danger" id="msg_sub_laporan"></small>
</div>
@endif


<script>
    new Choices($('#sub_laporan')[0])

</script>
