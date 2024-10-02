@extends('admin.layout.base')

@section('content')
    <div class="card mb-3">
        <div class="card-body p-3">
            <div class="col-md-12">
                <select class="form-control" name="lokasi" id="lokasi">
                    <option selected>-- Pilih Lokasi --</option>
                    @foreach ($kabupaten as $kab)
                        @foreach ($kab->kec as $kec)
                            <option value="{{ $kec->kd_kec }}">
                                {{ ucwords(strtolower($kab->nama_kab)) }}, {{ $kec->nama_kec }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                <small class="text-danger" id="msg_lokasi"></small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-flush table-hover table-click" width="100%">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Level</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table;
        new Choices($('select#lokasi')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('change', '#lokasi', function(e) {
            e.preventDefault()

            if (table) {
                table.clear().draw();
                table.destroy();
            }

            if ($(this).val().length > 0) {
                CreateTable($(this).val())
            }
        })

        $('.table').on('click', 'tbody tr', function(e) {
            var data = table.row(this).data();

            window.open('/master/users/' + data.id)
        })

        function CreateTable(lokasi) {
            table = $('.table').DataTable({
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "/master/users/lokasi/" + lokasi,
                columns: [{
                        data: 'namadepan',
                        name: 'namadepan'
                    },
                    {
                        data: 'l.deskripsi_level',
                        name: 'l.deskripsi_level'
                    },
                    {
                        data: 'j.nama_jabatan',
                        name: 'j.nama_jabatan'
                    }
                ]
            })
        }
    </script>
@endsection
