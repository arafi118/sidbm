<div class="table-responsive p-0">
    <table class="table align-items-center mb-0">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Anggota</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Proposal dalam proses
                </th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Pinjaman aktif
                </th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Alokasi pengajuan
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggota as $a)
                @php
                    $proposal = 0;
                    $aktif = 0;

                    $nomor = 1;
                    foreach ($a->pinjaman_anggota as $pinjaman) {
                        if ($nomor == 1) {
                            if ($pinjaman->status == 'A') {
                                $aktif = $pinjaman->id_pinkel;
                            }

                            if (in_array($pinjaman->status, ['P', 'V', 'W'])) {
                                $proposal = $pinjaman->id_pinkel;
                            }

                            $nomor++;
                            break;
                        }
                    }
                @endphp
                <tr>
                    <td>
                        <div class="d-flex px-3 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $a->namadepan }}</h6>
                                <p class="text-sm font-weight-normal text-secondary mb-0">
                                    {{ $a->nik }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if ($proposal != 0)
                            <a href="/detail/{{ $proposal }}" class="badge text-white bg-danger"
                                target="_blank">{{ $proposal }}</a>
                        @else
                            Tidak ada
                        @endif
                    </td>
                    <td class="align-middle text-center text-sm">
                        @if ($aktif != 0)
                            <a href="/detail/{{ $aktif }}" class="badge text-white bg-danger"
                                target="_blank">{{ $aktif }}</a>
                        @else
                            Tidak ada
                        @endif
                    </td>
                    <td class="align-middle text-end">
                        <div class="input-group input-group-static">
                            <input type="text" id="alokasi_pengajuan_{{ $a->nik }}"
                                name="alokasi_pengajuan_anggota[{{ $a->id }}]" class="form-control money"
                                placeholder="Alokasi Pengajuan" value="0.00" {{ $proposal != 0 ? 'disabled' : '' }}
                                autocomplete="off">
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(".money").maskMoney();
</script>
