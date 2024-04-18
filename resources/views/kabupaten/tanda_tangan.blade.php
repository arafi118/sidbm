@extends('kabupaten.layout.base')

@section('content')
    <form action="/kab/tanda_tangan/simpan" method="post" id="formTandaTangan" height>
        @csrf

        @if ($kab->tanda_tangan)
            <textarea class="tiny-mce-editor" name="tanda_tangan" id="tanda_tangan" rows="20">{!! json_decode($kab->tanda_tangan, true) !!}</textarea>
        @else
            <textarea class="tiny-mce-editor" name="tanda_tangan" id="tanda_tangan" rows="20">
<table class="p0" border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: 11px;">
<tbody>
<tr>
<td style="width: 33.3216%;">&nbsp;</td>
<td style="text-align: center; width: 21.0508%;">&nbsp;</td>
<td style="width: 45.5924%; text-align: center;" colspan="2">{tanggal}</td>
</tr>
<tr>
<td style="text-align: center; width: 33.3216%;">Mengetahui:</td>
<td style="text-align: center; width: 21.0508%;">&nbsp;</td>
<td style="text-align: center; width: 45.5924%;" colspan="2">Disajikan Oleh:</td>
</tr>
<tr>
<td style="text-align: center; width: 33.3216%;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
<td style="text-align: center; width: 21.0508%;">&nbsp;</td>
<td style="text-align: center; width: 22.6388%;">&nbsp;</td>
<td style="text-align: center; width: 22.9536%;">&nbsp;</td>
</tr>
<tr>
<td style="text-align: center; width: 33.3216%;"><strong>......................................</strong></td>
<td style="text-align: center; width: 21.0508%;"><strong>&nbsp;</strong></td>
<td style="text-align: center; width: 22.6388%;"><strong>......................................</strong></td>
<td style="text-align: center; width: 22.9536%;"><strong>......................................</strong></td>
</tr>
<tr>
<td style="text-align: center; width: 33.3216%;">Kadis PMD</td>
<td style="text-align: center; width: 21.0508%;">&nbsp;</td>
<td style="text-align: center; width: 22.6388%;">Kabid .......................</td>
<td style="text-align: center; width: 22.9536%;">Ketua Forkom Bumdesma</td>
</tr>
<tr>
<td style="text-align: center; width: 33.3216%;">&nbsp;</td>
<td style="text-align: center; width: 21.0508%;">&nbsp;</td>
<td style="text-align: center; width: 22.6388%;">&nbsp;</td>
<td style="text-align: center; width: 22.9536%;">&nbsp;</td>
</tr>
</tbody>
</table></textarea>
        @endif
    </form>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" id="simpanTandaTangan" class="btn btn-github btn-sm ms-2">
            Simpan Perubahan
        </button>
    </div>
@endsection


@section('script')
    <script>
        $(document).on('click', '#simpanTandaTangan', function(e) {
            e.preventDefault()

            tinymce.triggerSave()
            var form = $('#formTandaTangan')
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
