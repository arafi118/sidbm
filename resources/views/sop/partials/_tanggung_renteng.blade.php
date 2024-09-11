<form action="/pengaturan/tanggung_renteng/{{ $kec->id }}" method="post" id="TanggungRenteng">
    @csrf
    @method('PUT')

    <div class="my-3">
        <div id="tanggung-renteng-editor">
            <ol>
                {!! json_decode($kec->tanggung_renteng, true) !!}
            </ol>
        </div>
    </div>

    <textarea name="tanggung-renteng" id="tanggung-renteng" class="d-none"></textarea>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanTanggungRenteng" data-target="#TanggungRenteng"
        class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
