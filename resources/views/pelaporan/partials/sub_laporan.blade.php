@if ($file == 'calk')
    <div class="my-3">
        <div id="editor">
            <ol>
                {!! $keterangan !!}
            </ol>
        </div>
    </div>

    <textarea name="sub_laporan" id="sub_laporan" class="d-none"></textarea>

    <script>
        quill = new Quill('#editor', {
            theme: 'snow'
        });
    </script>
@else
    <div class="my-2">
        <label class="form-label" for="sub_laporan">Nama Sub Laporan</label>
        <select class="form-control" name="sub_laporan" id="sub_laporan">
            @if (count($data) > 0)
                <option value="">--- Pilih Sub {{ $laporan->nama_laporan }} ---</option>
            @else
                <option value="">--- Tidak Ada Sub Laporan ---</option>
            @endif
            @foreach ($data as $dt)
                <option value="{{ $dt['value'] }}">{{ $dt['title'] }}</option>
            @endforeach
        </select>
        <small class="text-danger" id="msg_sub_laporan"></small>
    </div>
@endif


<script>
    new Choices($('select#sub_laporan')[0])
</script>
