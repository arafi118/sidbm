@php
    use App\Models\PinjamanKelompok;

    $selected = false;

    $Kolom = 'col-md-9 col-7';
    if (!in_array('register_kelompok', Session::get('akses_menu'))) {
        $Kolom = 'col-md-12 col-12';
    }
@endphp

<div class="row">
    <div class="{{ $Kolom }}">
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
                        [{{ $pinjaman }}] [{{ $kel->kd_kelompok }}] {{ $kel->nama_kelompok }}
                        [{{ $kel->d->nama_desa }}]
                        [{{ $kel->ketua }}]
                    @else
                        [{{ $pinjaman }}] [{{ $kel->kd_kelompok }}] {{ $kel->nama_kelompok }} []
                        [{{ $kel->ketua }}]
                    @endif
                </option>
            @endforeach
        </select>
    </div>

    @if (in_array('register_kelompok', Session::get('akses_menu')))
        <div class="col-md-3 col-5 d-flex align-items-end">
            <div class="d-grid w-100 mb-2">
                <a href="/database/kelompok/register_kelompok" class="btn btn-info btn-sm mb-0">Register Kelompok</a>
            </div>
        </div>
    @endif
</div>

<script>
    new Choices($('#kelompok')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        },
        searchResultLimit: 50
    })
</script>
