<form action="/pengaturan/personalia/{{ $kec->id }}" method="post" id="FormPersonalia">
    @csrf
    @method('PUT')

    <div class="row">
        @foreach ($kec->personalia as $personalia)
            <div class="col-md-6">
                <div class="input-group input-group-static my-3">
                    <label for="sebutan">Sebutan</label>
                    <input autocomplete="off" type="text" name="sebutan[]" id="sebutan_{{ $personalia->id }}"
                        class="form-control" value="{{ $personalia->sebutan }}">
                    <small class="text-danger" id="msg_sebutan_{{ $personalia->id }}"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-static my-3">
                    <label for="nama">Nama</label>
                    <input autocomplete="off" type="text" name="nama[]" id="nama_{{ $personalia->id }}"
                        class="form-control" value="{{ $personalia->nama }}">
                    <small class="text-danger" id="msg_nama_{{ $personalia->id }}"></small>
                </div>
            </div>
        @endforeach
    </div>
</form>

<div class="d-none" id="newPersonalia">
    <div class="col-md-6">
        <div class="input-group input-group-static my-3">
            <label for="sebutan">Sebutan</label>
            <input autocomplete="off" type="text" name="sebutan[]" id="sebutan" class="form-control"
                value="">
            <small class="text-danger" id="msg_sebutan"></small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group input-group-static my-3">
            <label for="nama">Nama</label>
            <input autocomplete="off" type="text" name="nama[]" id="nama" class="form-control" value="">
            <small class="text-danger" id="msg_nama"></small>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button type="button" id="TambahPersonalia" class="btn btn-sm btn-info mb-0">
        Tambah Personalia
    </button>
    <button type="button" id="SimpanPengelola" data-target="#FormPersonalia"
        class="btn btn-sm btn-github mb-0 btn-simpan ms-2">
        Simpan Perubahan
    </button>
</div>
