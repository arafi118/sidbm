<div class="col-sm-6">
    <div class="my-2">
        <label class="form-label" for="sumber_dana">Sumber Dana</label>
        <select class="form-control" name="sumber_dana" id="sumber_dana">
            <option value="">-- {{ $label1 }} --</option>
            @foreach ($rek1 as $r1)
                <option value="{{ $r1->kode_akun }}">
                    {{ $r1->kode_akun }}. {{ $r1->nama_akun }}
                </option>
            @endforeach
        </select>
        <small class="text-danger" id="msg_sumber_dana"></small>
    </div>
</div>
<div class="col-sm-6">
    <div class="my-2">
        <label class="form-label" for="disimpan_ke">{{ $label2 }}</label>
        <select class="form-control" name="disimpan_ke" id="disimpan_ke">
            <option value="">-- {{ $label2 }} --</option>
            @foreach ($rek2 as $r2)
                <option value="{{ $r2->kode_akun }}">
                    {{ $r2->kode_akun }}. {{ $r2->nama_akun }}
                </option>
            @endforeach
        </select>
        <small class="text-danger" id="msg_disimpan_ke"></small>
    </div>
</div>


<script>
    var sumber = new Choices($('#sumber_dana')[0])
    var simpan = new Choices($('#disimpan_ke')[0])
</script>
