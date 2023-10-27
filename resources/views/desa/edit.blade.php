<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="NamaDesa">
            Edit Desa {{ $desa->nama_desa }} [{{ $desa->kd_desa }}]
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="/database/desa/{{ $desa->kd_desa }}" method="post" id="FormEditDesa">
            @csrf
            @method('PUT')
            <input type="hidden" name="kd_desa" id="kd_desa" value="{{ $desa->kd_desa }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="nama_desa">Nama Desa</label>
                        <input type="text" name="nama_desa" id="nama_desa" class="form-control"
                            value="{{ $desa->nama_desa }}">
                        <small class="text-danger" id="msg_nama_desa"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="telp_desa">Telpon</label>
                        <input type="text" name="telp_desa" id="telp_desa" class="form-control"
                            value="{{ $desa->telp_desa }}">
                        <small class="text-danger" id="msg_telp_desa"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="alamat_desa">Alamat Desa</label>
                        <input type="text" name="alamat_desa" id="alamat_desa" class="form-control"
                            value="{{ $desa->alamat_desa }}">
                        <small class="text-danger" id="msg_alamat_desa"></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="kades">Nama Kades/Lurah</label>
                        <input type="text" name="kades" id="kades" class="form-control"
                            value="{{ $desa->kades }}">
                        <small class="text-danger" id="msg_kades"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="no_kades">Telp Kades/Lurah</label>
                        <input type="text" name="no_kades" id="no_kades" class="form-control"
                            value="{{ $desa->no_kades }}">
                        <small class="text-danger" id="msg_no_kades"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="nip">NIP Kades/Lurah</label>
                        <input type="text" name="nip" id="nip" class="form-control"
                            value="{{ $desa->nip }}">
                        <small class="text-danger" id="msg_nip"></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="sekdes">Nama Sekdes</label>
                        <input type="text" name="sekdes" id="sekdes" class="form-control"
                            value="{{ $desa->sekdes }}">
                        <small class="text-danger" id="msg_sekdes"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="no_sekdes">Telp Sekdes</label>
                        <input type="text" name="no_sekdes" id="no_sekdes" class="form-control"
                            value="{{ $desa->no_sekdes }}">
                        <small class="text-danger" id="msg_no_sekdes"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="deskripsi_desa">Deskripsi Desa/Kelurahan</label>
                        <input type="text" name="deskripsi_desa" id="deskripsi_desa" class="form-control"
                            value="{{ $desa->deskripsi_desa }}">
                    </div>
                    <small class="text-danger" id="msg_deskripsi_desa"></small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-static my-3">
                        <label for="ked">Nama KED/LPMD/BPD</label>
                        <input type="text" name="ked" id="ked" class="form-control"
                            value="{{ $desa->ked }}">
                    </div>
                    <small class="text-danger" id="msg_ked"></small>
                </div>
                <div class="col-md-4">
                    <div class="my-2">
                        <label class="form-label" for="sebutan">Sebutan Desa</label>
                        <select class="form-control" name="sebutan" id="sebutan">
                            @foreach ($sebutan as $seb)
                                <option value="{{ $seb->id }}"
                                    {{ $seb->id == $desa->sebutan ? 'selected' : '' }}>
                                    {{ $seb->sebutan_desa }} [{{ $seb->sebutan_kades }}]
                                </option>
                            @endforeach
                        </select>
                        <small class="text-danger" id="msg_sebutan"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="my-2">
                        <label class="form-label" for="jadwal_angsuran_desa">Jadwal Angsuran Desa</label>
                        <select class="form-control" name="jadwal_angsuran_desa" id="jadwal_angsuran_desa">
                            <option value="0">Setiap Tanggal Cair</option>
                            @for ($i = 1; $i <= 31; $i++)
                                @php $tgl=sprintf("%02d", $i); @endphp <option value="{{ $tgl }}"
                                    {{ $desa->jadwal_angsuran_desa == $tgl ? 'selected' : '' }}>
                                    Setiap Tanggal {{ $tgl }}</option>
                            @endfor
                        </select>
                        <small class="text-danger" id="msg_jadwal_angsuran_desa"></small>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="simpanDesa" class="btn btn-sm btn-github">Simpan</button>
    </div>
</div>

<script>
    new Choices($('#sebutan')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
    new Choices($('#jadwal_angsuran_desa')[0], {
        shouldSort: false,
        fuseOptions: {
            threshold: 0.1,
            distance: 1000
        }
    })
</script>
