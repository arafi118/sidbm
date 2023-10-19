@php
    use App\Models\PinjamanKelompok;
@endphp

<div class="row">
    <div class="col-md-9 col-7">
        <div class="my-2">
            <select class="form-control" name="kelompok" id="kelompok">
                @foreach ($kelompok as $kel)
                    @php
                        if ($kel->pinjaman_count > 0) {
                            $status = $kel->pinjaman->status;
                            if ($status == 'P' || $status == 'V' || $status == 'W') {
                                continue;
                            }
                        }
                    @endphp
                    <option {{ $kel->id == $id_kel ? 'selected' : '' }} value="{{ $kel->id }}">
                        @if (isset($kel->d))
                            {{ $kel->nama_kelompok }} [{{ $kel->d->nama_desa }}] [{{ $kel->ketua }}]
                        @else
                            {{ $kel->nama_kelompok }} [] [{{ $kel->ketua }}]
                        @endif
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
