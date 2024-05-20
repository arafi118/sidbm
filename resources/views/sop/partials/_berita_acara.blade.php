<form action="/pengaturan/berita_acara/{{ $kec->id }}" method="post" id="BeritaAcara">
    @csrf
    @method('PUT')

    <div class="my-3">
        <div id="ba-editor">{!! json_decode($kec->berita_acara, true) !!}</div>
    </div>

    <textarea name="ba" id="ba" class="d-none"></textarea>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanBeritaAcara" data-target="#BeritaAcara"
        class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
