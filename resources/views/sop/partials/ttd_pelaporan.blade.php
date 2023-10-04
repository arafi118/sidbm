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

            <div class="d-flex justify-content-end mt-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#kataKunci" class="btn btn-info btn-sm">
                    Kata Kunci
                </button>
                <button type="button" id="simpanTtdPelaporan" class="btn btn-github btn-sm ms-2">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kataKunci" tabindex="-1" aria-labelledby="kataKunciLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="kataKunciLabel">Kata Kunci</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
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
