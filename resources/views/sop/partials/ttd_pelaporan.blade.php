@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body p-2">
            <form action="/pengaturan/sop/simpanttdpelaporan" method="post" id="formTtdPelaporan" height>
                @csrf

                <input type="hidden" name="field" id="field" value="tanda_tangan_pelaporan">
                <textarea class="tiny-mce-editor" name="tanda_tangan" id="tanda_tangan" rows="20">
@if ($kec->ttd)
{!! json_decode($kec->ttd->tanda_tangan_pelaporan, true) !!}
@else
@endif
</textarea>
            </form>

            @if (!$tanggal)
                <small class="text-danger">
                    Masukkan <span style="text-transform: lowercase">
                        *{tanggal}* pada form tanda tangan untuk menuliskan tanggal laporan dibuat. <b>Hapus tanda bintang
                            (*)</b>
                    </span>
                </small>
            @endif
            <div class="d-flex justify-content-end mt-3">
                <button type="button" id="simpanTtdPelaporan" class="btn btn-github btn-sm ms-2">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '#simpanTtdPelaporan', function(e) {
            e.preventDefault()

            tinymce.triggerSave()
            var form = $('#formTtdPelaporan')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toastr('success', result.msg)
                    }
                }
            })
        })
    </script>
@endsection
