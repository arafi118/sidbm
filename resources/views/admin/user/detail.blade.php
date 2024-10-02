@extends('admin.layout.base')

@section('content')
    <div class="nav-wrapper position-relative end-0">
        <ul class="nav nav-pills nav-fill p-1 d-none" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-0 px-0 py-1 active" id="TabAksesMenu" data-bs-toggle="tab" href="#HakAksesMenu"
                    role="tab" aria-controls="HakAksesMenu" aria-selected="true">
                    Hak Akses Menu
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-0 px-0 py-1" id="TabAksesTombol" data-bs-toggle="tab" href="#HakAksesTombol"
                    role="tab" aria-controls="HakAksesTombol" aria-selected="false">
                    Hak Akses Tombol
                </a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="HakAksesMenu" role="tabpanel" aria-labelledby="HakAksesMenu">
                @include('admin.user.partials.akses_menu')
            </div>
            <div class="tab-pane fade" id="HakAksesTombol" role="tabpanel" aria-labelledby="HakAksesTombol">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Pengaturan Hak Akses Tombol</h5>
                    </div>
                    <div class="card-body pt-0" id="HakAksesTombolUser"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', 'input[type=checkbox]', function() {
            let Id = $(this).attr('id')
            let DataParent = $(this).attr('data-parent')
            let DataChild = $(this).attr('data-child')

            if (DataParent) {
                $('#' + DataParent).prop('checked', true)
            }

            if (DataChild) {
                $('#' + DataChild).prop('checked', true)
            }

            if (!$(this).prop('checked')) {
                $('input[data-parent=' + Id + ']').prop('checked', false)
                $('input[data-child=' + Id + ']').prop('checked', false)
            } else {
                $('input[data-parent=' + Id + ']').prop('checked', true)
                $('input[data-child=' + Id + ']').prop('checked', true)
            }

            if ($('input[data-parent=' + DataParent + ']:checked').length == '0') {
                $('#' + DataParent).prop('checked', false)
            }

            if ($('input[data-child=' + DataChild + ']:checked').length == '0') {
                $('#' + DataChild).trigger('click')
            }
        })

        $(document).on('click', '#NextStep', function(e) {
            e.preventDefault()

            var form = $('#FormAccesMenuUser')
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#HakAksesTombolUser').html(result.view)
                        $('#TabAksesTombol').tab('show')
                    } else {
                        Swal.fire('Error', result.msg, 'error')
                    }
                }
            })
        })

        $(document).on('click', '#BackStep', function(e) {
            e.preventDefault()

            $('#TabAksesMenu').tab('show')
        })

        $(document).on('click', '#Save', function(e) {
            e.preventDefault()

            var form = $('#FormPengaturanHakAksesUser')
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#TabAksesMenu').tab('show')
                        Swal.fire('Berhasil', result.msg, 'success').then(() => {
                            window.close()
                        })
                    } else {
                        Swal.fire('Error', result.msg, 'error')
                    }
                }
            })
        })
    </script>
@endsection
