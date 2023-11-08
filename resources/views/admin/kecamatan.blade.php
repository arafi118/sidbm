@extends('admin.layout.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/master/kecamatan" method="post" id="formAksi">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="my-2">
                            <label class="form-label" for="kecamatan">Kecamatan</label>
                            <select class="form-control" name="kecamatan" id="kecamatan">
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id }}">
                                        {{ $kec->id }}. {{ $kec->nama_kec }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger" id="msg_desa"></small>
                        </div>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-end">
                <button type="button" id="aksi" class="btn btn-sm btn-github">Action</button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body p-2" id="LayoutPreview">
            <div class="p-5"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        new Choices($('#kecamatan')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('click', '#aksi', function(e) {
            e.preventDefault()

            var form = $('#formAksi')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#LayoutPreview').html(result.success)
                    }
                }
            })
        })
    </script>
@endsection
