@php
    $ketua = $perguliran->kelompok->ketua;
    $sekretaris = $perguliran->kelompok->sekretaris;
    $bendahara = $perguliran->kelompok->bendahara;
    if ($perguliran->struktur_kelompok) {
        $struktur_kelompok = json_decode($perguliran->struktur_kelompok, true);
        $ketua = $struktur_kelompok['ketua'];
        $sekretaris = $struktur_kelompok['sekretaris'];
        $bendahara = $struktur_kelompok['bendahara'];
    }
@endphp

<form action="/perguliran/{{ $perguliran->id }}" method="post" id="FormEditProposal">
    @csrf
    @method('PUT')

    <input type="hidden" name="_id" id="_id" value="{{ $perguliran->id }}">
    <input type="hidden" name="status" id="status" value="P">
    <div class="row">
        <div class="col-md-3">
            <div class="input-group input-group-static my-3">
                <label for="tgl_verifikasi">Tgl Proposal</label>
                <input autocomplete="off" type="text" name="tgl_proposal" id="tgl_proposal" class="form-control date"
                    value="{{ Tanggal::tglIndo($perguliran->tgl_proposal) }}">
                <small class="text-danger" id="msg_tgl_proposal"></small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-static my-3">
                <label for="proposal">Pengajuan Rp.</label>
                <input autocomplete="off" type="text" name="proposal" id="proposal" class="form-control money"
                    value="{{ number_format($perguliran->proposal, 2) }}">
                <small class="text-danger" id="msg_proposal"></small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-static my-3">
                <label for="jangka_proposal">Jangka</label>
                <input autocomplete="off" type="number" name="jangka_proposal" id="jangka_proposal"
                    class="form-control" value="{{ $perguliran->jangka }}">
                <small class="text-danger" id="msg_jangka_proposal"></small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-static my-3">
                <label for="pros_jasa_proposal">Prosentase Jasa (%)</label>
                <input autocomplete="off" type="number" name="pros_jasa_proposal" id="pros_jasa_proposal"
                    class="form-control" value="{{ $perguliran->pros_jasa }}">
                <small class="text-danger" id="msg_pros_jasa_proposal"></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="my-2">
                <label class="form-label" for="jenis_jasa_proposal">Jenis Jasa</label>
                <select class="form-control" name="jenis_jasa_proposal" id="jenis_jasa_proposal">
                    @foreach ($jenis_jasa as $jj)
                        <option {{ $jj->id == $perguliran->jenis_jasa ? 'selected' : '' }} value="{{ $jj->id }}">
                            {{ $jj->nama_jj }}
                        </option>
                    @endforeach
                </select>
                <small class="text-danger" id="msg_jenis_jasa_proposal"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="my-2">
                <label class="form-label" for="jenis_produk_pinjaman">Jenis Produk Pinjaman</label>
                <select class="form-control" name="jenis_produk_pinjaman" id="jenis_produk_pinjaman">
                    @foreach ($jenis_pp as $jpp)
                        <option {{ $jenis_pp_dipilih == $jpp->id ? 'selected' : '' }} value="{{ $jpp->id }}">
                            {{ $jpp->nama_jpp }} ({{ $jpp->deskripsi_jpp }})
                        </option>
                    @endforeach
                </select>
                <small class="text-danger" id="msg_jenis_produk_pinjaman"></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="my-2">
                <label class="form-label" for="sistem_angsuran_pokok_proposal">Sistem Angs. Pokok</label>
                <select class="form-control" name="sistem_angsuran_pokok_proposal" id="sistem_angsuran_pokok_proposal">
                    @foreach ($sistem_angsuran as $sa)
                        <option {{ $sa->id == $perguliran->sistem_angsuran ? 'selected' : '' }}
                            value="{{ $sa->id }}">
                            {{ $sa->nama_sistem }} ({{ $sa->deskripsi_sistem }})
                        </option>
                    @endforeach
                </select>
                <small class="text-danger" id="msg_sistem_angsuran_pokok_proposal"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="my-2">
                <label class="form-label" for="sistem_angsuran_jasa_proposal">Sistem Angs. Jasa</label>
                <select class="form-control" name="sistem_angsuran_jasa_proposal" id="sistem_angsuran_jasa_proposal">
                    @foreach ($sistem_angsuran as $sa)
                        <option {{ $sa->id == $perguliran->sa_jasa ? 'selected' : '' }} value="{{ $sa->id }}">
                            {{ $sa->nama_sistem }} ({{ $sa->deskripsi_sistem }})
                        </option>
                    @endforeach
                </select>
                <small class="text-danger" id="msg_sistem_angsuran_jasa_proposal"></small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div class="text-center fw-bold">
                Struktur Kelompok
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="ketua">Ketua</label>
                        <input autocomplete="off" type="text" name="ketua" id="ketua" class="form-control"
                            value="{{ $ketua }}">
                        <small class="text-danger" id="msg_ketua"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="sekretaris">Sekretaris</label>
                        <input autocomplete="off" type="text" name="sekretaris" id="sekretaris"
                            class="form-control" value="{{ $sekretaris }}">
                        <small class="text-danger" id="msg_sekretaris"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="bendahara">Bendahara</label>
                        <input autocomplete="off" type="text" name="bendahara" id="bendahara"
                            class="form-control" value="{{ $bendahara }}">
                        <small class="text-danger" id="msg_bendahara"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    new Choices($('#jenis_jasa_proposal')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
    new Choices($('#sistem_angsuran_pokok_proposal')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
    new Choices($('#sistem_angsuran_jasa_proposal')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
    new Choices($('#jenis_produk_pinjaman')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })

    $(".money").maskMoney();

    $(".date").flatpickr({
        dateFormat: "d/m/Y"
    })
</script>
