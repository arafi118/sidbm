@extends('layouts.base')

@section('css')
    <style>
        .cke_notifications_area {
            display: none
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/pengaturan/custom_calk/{{ $kec->id }}" method="post" id="FormCalk">
                @csrf
                @method('PUT')

                <textarea name="customize_calk" id="customize_calk">{{ $pointA }}</textarea>
                <textarea name="calk" id="calk" class="d-none"></textarea>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-github mt-3">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="/vendor/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('customize_calk');

        $(document).on('submit', '#FormCalk', function(e) {
            e.preventDefault();

            var form = $('#FormCalk');
            $('#calk').val(CKEDITOR.instances.customize_calk.getData())

            $.ajax({
                type: "PUT",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    Toastr('success', result.msg)
                },
                error: function(xhr) {
                    Toastr('error', xhr.responseJSON.msg)
                }
            });
        });
    </script>
@endsection
