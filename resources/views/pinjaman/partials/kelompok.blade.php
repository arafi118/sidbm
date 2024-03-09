@php
    use App\Models\PinjamanKelompok;

    $selected = false;
@endphp

<div class="row">
    <div class="col-md-9 col-7">
        <select class="form-control mb-0" name="kelompok" id="kelompok">
            @foreach ($kelompok as $kel)
                @php
                    $pinjaman = 'N';
                    if ($kel->pinjaman_count > 0) {
                        $status = $kel->pinjaman->status;
                        $pinjaman = $status;
                    }

                    $select = false;
                    if (!($pinjaman == 'P' || $pinjaman == 'V' || $pinjaman == 'W') && !$selected) {
                        $select = true;
                        $selected = true;
                    }

                    if ($id_kel > 0) {
                        $select = false;
                    }

                    if ($kel->id == $id_kel) {
                        $select = true;
                    }
                @endphp
                <option {{ $select ? 'selected' : '' }} value="{{ $kel->id }}">
                    @if (isset($kel->d))
                        [{{ $pinjaman }}] {{ $kel->nama_kelompok }} [{{ $kel->d->nama_desa }}]
                        [{{ "$selected" }}]
                    @else
                        [{{ $pinjaman }}] {{ $kel->nama_kelompok }} [] [{{ "$selected" }}]
                    @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-5 d-flex align-items-end">
        <div class="d-grid w-100 mb-2">
            <a href="/database/kelompok/register_kelompok" class="btn btn-info btn-sm mb-0">Register Kelompok</a>
        </div>
    </div>
</div>

<script>
    new Choices($('#kelompok')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
</script>
