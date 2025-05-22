@extends('layouts.base')

@section('content')
    <form action="/pengaturan/simpan_tanda_tangan" method="post" id="formTandaTangan">
        @csrf
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="my-2">
                            <label class="form-label" for="jenis_laporan">Jenis Laporan</label>
                            <select class="form-control" name="jenis_laporan" id="jenis_laporan">
                                <option value="">---</option>
                                <option value="dokumen_pinjaman">Dokumen Pinjaman</option>
                                <option value="pelaporan">Pelaporan</option>
                            </select>
                            <small class="text-danger" id="msg_jenis_laporan"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="my-2">
                            <label class="form-label" for="dokumen">Nama Dokumen</label>
                            <select class="form-control" name="dokumen" id="dokumen">
                                <option value="">---</option>
                            </select>
                            <small class="text-danger" id="msg_dokumen"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-body p-2">
                <textarea class="tiny-mce-editor" name="tanda_tangan" id="tanda_tangan" rows="20"></textarea>

                <div class="d-flex justify-content-end mt-2">
                    <button type="button" id="simpanTandaTangan" class="btn btn-github btn-sm mb-0">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        var daftarLaporanPinjaman = @json($dokumenPinjaman);
        var tandaTanganDokumen = @json($tandaTangan);

        var daftarPelaporan = [{
            id: 0,
            title: 'Semua Pelaporan'
        }];

        var jenis_laporan = new Choices($('select#jenis_laporan')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        var jenisDokumen = new Choices($('select#dokumen')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('change', '#jenis_laporan', function() {
            var jenis = $(this).val();

            var listLaporan = [{
                value: '',
                label: '---'
            }]

            if (jenis == 'dokumen_pinjaman') {
                daftarLaporanPinjaman.map(function(item) {
                    var jenis_dokumen = item.jenis_dokumen.replace('_', ' ');

                    listLaporan.push({
                        value: item.id,
                        label: item.title + ' (' + jenis_dokumen + ')'
                    })
                })
            }

            if (jenis == 'pelaporan') {
                daftarPelaporan.map(function(item) {
                    listLaporan.push({
                        value: item.id,
                        label: item.title
                    })
                })
            }

            jenisDokumen.setChoices(listLaporan, 'value', 'label', true);
            jenisDokumen.setChoiceByValue('', true);
        });

        $(document).on('change', '#dokumen', function() {
            var id = $(this).val();

            setFormTandaTangan(id);
        });

        $(document).on('click', '#simpanTandaTangan', function() {
            tinymce.triggerSave()
            var form = $('#formTandaTangan');

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toastr('success', result.msg)
                        tandaTanganDokumen[result.data.dokumen_pinjaman_id] = result.data.tanda_tangan;
                    }
                },
                error: function(xhr) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#msg_' + key).html(value);
                    });
                }
            })
        });

        function setFormTandaTangan(id) {
            if (tandaTanganDokumen[id]) {
                tinymce.activeEditor.setContent(tandaTanganDokumen[id]);
            } else {
                tinymce.activeEditor.setContent('');
            }
        }
    </script>
@endsection
