<form action="/pengaturan/spk/{{ $kec->id }}" method="post" id="FormSPK">
    @csrf
    @method('PUT')

    <div class="my-3">
        <div id="editor">
            <ol>
                {!! json_decode($kec->redaksi_spk, true) !!}
            </ol>
        </div>
    </div>

    <textarea name="spk" id="spk" class="d-none"></textarea>
</form>

<div class="d-flex justify-content-end">
    <button type="button" id="SimpanSPK" data-target="#FormSPK" class="btn btn-sm btn-github mb-0 btn-simpan">
        Simpan Perubahan
    </button>
</div>
