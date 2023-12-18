@extends('layouts.base')

@section('content')
    <div class="row mb-5">
        <div class="col-lg-3">
            <div class="card position-sticky top-10">
                <ul class="nav flex-column bg-white border-radius-lg p-3">
                    <li class="nav-item mb-2">
                        <b>Pengaturan</b>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#lembaga">
                            <i class="material-icons text-lg me-2">business</i>
                            <span class="text-sm">Identitas Lembaga</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#pengelola">
                            <i class="material-icons text-lg me-2">assignment_ind</i>
                            <span class="text-sm">Sebutan Pengelola</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#pinjaman">
                            <i class="material-icons text-lg me-2">equalizer</i>
                            <span class="text-sm">Sistem Pinjaman</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#asuransi">
                            <i class="material-icons text-lg me-2">account_balance_wallet</i>
                            <span class="text-sm">Pengaturan Asuransi</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#redaksi_spk">
                            <i class="material-icons text-lg me-2">description</i>
                            <span class="text-sm">Redaksi Dok. SPK</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#calk">
                            <i class="material-icons text-lg me-2">insert_drive_file</i>
                            <span class="text-sm">Pengaturan CALK</span>
                        </a>
                    </li> --}}
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#logo">
                            <i class="material-icons text-lg me-2">crop_original</i>
                            <span class="text-sm">Logo</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" id="SimpanSaldo" href="#">
                            <i class="material-icons text-lg me-2">crop_original</i>
                            <span class="text-sm">Simpan Saldo</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9 mt-lg-0 mt-4">
            <div class="card" id="lembaga">
                <div class="card-header">
                    <h5 class="mb-0">Identitas Lembaga</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._lembaga')
                </div>
            </div>
            <div class="card mt-4" id="pengelola">
                <div class="card-header">
                    <h5 class="mb-0">Sebutan Pengelola Bumdesma</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._pengelola')
                </div>
            </div>
            <div class="card mt-4" id="pinjaman">
                <div class="card-header">
                    <h5 class="mb-0">Sistem Pinjaman</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._pinjaman')
                </div>
            </div>
            <div class="card mt-4" id="asuransi">
                <div class="card-header">
                    <h5 class="mb-0">Pengaturan Asuransi</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._asuransi')
                </div>
            </div>
            <div class="card mt-4" id="redaksi_spk">
                <div class="card-header">
                    <h5 class="mb-0">Redaksi Dokumen SPK</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._spk')
                </div>
            </div>
            <div class="card mt-4" id="logo">
                <div class="card-header">
                    <h5 class="mb-0">Upload Logo</h5>
                </div>
                <div class="card-body pt-0">
                    @include('sop.partials._logo')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var tahun = "{{ date('Y') }}"
        var bulan = "{{ date('m') }}"

        $(".money").maskMoney();
        new Choices($('#pembulatan')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
        new Choices($('#jenis_asuransi')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        let childWindow;
        $(document).on('click', '#SimpanSaldo', function(e) {
            e.preventDefault()

            childWindow = window.open('/simpan_saldo?bulan=01&tahun=' + tahun, '_blank');
        })

        window.addEventListener('message', function(event) {
            if (event.data === 'closed') {
                window.location.reload()
            }
        })

        $(document).on('click', '.btn-simpan', async function(e) {
            e.preventDefault()

            if ($(this).attr('id') == 'SimpanSPK') {
                await $('#spk').val(quill.container.firstChild.innerHTML)
            }

            var form = $($(this).attr('data-target'))
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toastr('success', result.msg)

                        if (result.nama_lembaga) {
                            $('#nama_lembaga_sort').html(result.nama_lembaga)
                        }
                    }
                },
                error: function(result) {
                    const respons = result.responseJSON;

                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error')
                    $.map(respons, function(res, key) {
                        $('#' + key).parent('.input-group.input-group-static').addClass(
                            'is-invalid')
                        $('#msg_' + key).html(res)
                    })
                }
            })
        })

        $(document).on('click', '#EditLogo', function(e) {
            e.preventDefault()

            $('#logo_kec').trigger('click')
        })

        $(document).on('change', '#logo_kec', function(e) {
            e.preventDefault()

            var logo = $(this).get(0).files[0]
            if (logo) {
                var form = $('#FormLogo')
                var formData = new FormData(document.querySelector('#FormLogo'));
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result) {
                        if (result.success) {
                            var reader = new FileReader();

                            reader.onload = function() {
                                $("#previewLogo").attr("src", reader.result);
                                $(".colored-shadow").css('background-image',
                                    "url(" + reader.result + ")")
                            }

                            reader.readAsDataURL(logo);
                            Toastr('success', result.msg)
                        } else {
                            Toastr('error', result.msg)
                        }
                    }
                })
            }
        })
    </script>
@endsection
