<form action="/pengaturan/lembaga/{{ $kec->id }}" method="post" id="FormLembaga">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-2">
            <div class="input-group input-group-static my-3">
                <label for="id">ID.</label>
                <input autocomplete="off" type="text" name="id" id="id" class="form-control"
                    value="{{ $kec->id }}" readonly>
                <small class="text-danger" id="msg_id"></small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-static my-3">
                <label for="kd_kec">Kode Kec.</label>
                <input autocomplete="off" type="text" name="kd_kec" id="kd_kec" class="form-control"
                    value="{{ $kec->kd_kec }}" readonly>
                <small class="text-danger" id="msg_kd_kec"></small>
            </div>
        </div>
        <div class="col-md-8">
            <div class="input-group input-group-static my-3">
                <label for="nama_bumdesma">Nama Bumdesma</label>
                <input autocomplete="off" type="text" name="nama_bumdesma" id="nama_bumdesma" class="form-control"
                    value="{{ $kec->nama_lembaga_sort }}">
                <small class="text-danger" id="msg_nama_bumdesma"></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-static my-3">
                <label for="nomor_badan_hukum">Badan Hukum No. </label>
                <input autocomplete="off" type="text" name="nomor_badan_hukum" id="nomor_badan_hukum"
                    class="form-control" value="{{ $kec->nomor_bh }}">
                <small class="text-danger" id="msg_nomor_badan_hukum"></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-static my-3">
                <label for="telpon">Telpon</label>
                <input autocomplete="off" type="text" name="telpon" id="telpon" class="form-control"
                    value="{{ $kec->telpon_kec }}">
                <small class="text-danger" id="msg_telpon"></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group input-group-static my-3">
                <label for="email">Email</label>
                <input autocomplete="off" type="email" name="email" id="email" class="form-control"
                    value="{{ $kec->email_kec }}">
                <small class="text-danger" id="msg_email"></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group input-group-static my-3">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat">{{ $kec->alamat_kec }}</textarea>
                <small class="text-danger" id="msg_alamat"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="web_utama">Web Utama</label>
                <input autocomplete="off" type="text" name="web_utama" id="web_utama" class="form-control"
                    value="{{ $kec->web_kec }}" readonly>
                <small class="text-danger" id="msg_web_utama"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="web_alternatif">Web Alternatif</label>
                <input autocomplete="off" type="text" name="web_alternatif" id="web_alternatif" class="form-control"
                    value="{{ $kec->web_alternatif }}" readonly>
                <small class="text-danger" id="msg_web_alternatif"></small>
            </div>
        </div>
    </div>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanLembaga" data-target="#FormLembaga"
        class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
