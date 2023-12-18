@extends('layouts.base')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="post" id="FormTahunTutupBuku">
                @csrf

                <input type="hidden" name="tgl_pakai" id="tgl_pakai" value="{{ $kec->tgl_pakai }}">
                <div class="col-12">
                    <div class="my-2">
                        <label class="form-label" for="tahun">Tahun</label>
                        <select class="form-control" name="tahun" id="tahun">
                            @php
                                $tgl_pakai = $kec->tgl_pakai;
                                $th_pakai = explode('-', $tgl_pakai)[0];
                            @endphp
                            @for ($i = $th_pakai; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <small class="text-danger" id="msg_tahun"></small>
                    </div>

                    <input type="hidden" name="pembagian_laba" id="pembagian_laba" value="true">
                </div>
            </form>

            <div class="d-flex justify-content-end">
                <button type="button" id="TutupBuku" {{ date('m') <= 10 ? 'disabled' : '' }}
                    class="btn btn-sm btn-github mb-0 ms-3">1. Tutup Buku</button>
                <button type="button" id="PembagianLaba" class="btn btn-sm btn-instagram mb-0 ms-3">
                    2. Simpan Alokasi Laba
                </button>
            </div>
        </div>
    </div>

    <div id="LayoutPreview">
        <div class="card">
            <div class="card-body p-3">
                <div class="p-5"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var tahun = "{{ date('Y') }}"
        var bulan = "{{ date('m') }}"

        new Choices($('select#tahun')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('change', 'select#tahun', function(e) {
            e.preventDefault()

            var tahun_val = $(this).val()
            if ((tahun == tahun_val && bulan <= 10) || (tahun_val < tahun)) {
                $('#TutupBuku').prop("disabled", true)
            } else {
                $('#TutupBuku').prop("disabled", false)
            }
        })

        $(document).on('click', '#TutupBuku', function(e) {
            e.preventDefault()
            $('#FormTahunTutupBuku').attr('action', '/transaksi/tutup_buku/saldo')
            $('#LayoutPreview').html(
                '<div class="card"><div class="card-body p-3"><div class="p-5"></div></div></div>')

            var form = $('#FormTahunTutupBuku')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        $('#LayoutPreview').html(result.view)
                    }
                }
            })
        })

        $(document).on('click', '#PembagianLaba', function(e) {
            e.preventDefault()
            $('#FormTahunTutupBuku').attr('action', '/transaksi/tutup_buku')

            $('#FormTahunTutupBuku').submit()
        })
    </script>
@endsection
