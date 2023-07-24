@php
use App\Models\PinjamanKelompok;
@endphp

<div class="row">
    <div class="col-md-9 col-7">
        <div class="my-2">
            <select class="form-control" name="kelompok" id="kelompok">
                @foreach ($kelompok as $kel)
                @php
                $pinkel = PinjamanKelompok::where('id_kel', $kel->id)->orderBy('tgl_proposal','DESC');
                if ($pinkel->count() > 0) {
                $status = $pinkel->first()->status;
                if ($status == 'P' || $status == 'V' || $status == 'W') {
                continue;
                }
                }
                @endphp
                <option value="{{ $kel->id }}">
                    {{ $kel->nama_kelompok }} [{{ $kel->d->nama_desa }}] [{{ $kel->ketua }}]
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3 col-5">
        <div class="d-grid">
            <a href="/database/kelompok/register_kelompok" class="btn btn-info">Register Kelompok</a>
        </div>
    </div>
</div>

<script>
    new Choices($('#kelompok')[0])

</script>
