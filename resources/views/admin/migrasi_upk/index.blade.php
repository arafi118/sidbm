@extends('admin.layout.base')

@section('content')
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <form action="/master/migrasi_upk" method="post" id="FormMigrasi" target="_blank">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <div class="my-2">
                                    <label class="form-label" for="server">Server</label>
                                    <select class="form-control" name="server" id="server">
                                        <option value="net">NET</option>
                                        <option value="com">COM</option>
                                        <option value="new">NEW</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="my-2">
                                    <label class="form-label" for="lokasi">Lokasi</label>
                                    <select class="form-control" name="lokasi" id="lokasi">
                                        <option value="">---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="my-2">
                                    <label class="form-label" for="lokasi_baru">Lokasi Baru</label>
                                    <select class="form-control" name="lokasi_baru" id="lokasi_baru">
                                        <option value="">---</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->kode }}#{{ $kec->nama_kab }}#{{ $kec->nama_kec }}">
                                                {{ $kec->nama_kab }}, {{ $kec->nama_kec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex justify-content-end">
                        <button type="button" id="BtnMigrasi" class="btn btn-sm btn-github mb-0">Migrasi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    {{--  --}}
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body p-5">
                    <div class="p-5"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var inputServer = new Choices($('#server')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
        var inputLokasi = new Choices($('#lokasi')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
        var lokasiBaru = new Choices($('#lokasi_baru')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })

        $(document).on('change', '#server', function() {
            ambilLokasi()
        })

        $(document).on('change', '#lokasi', function() {
            var value = $(this).val().split('#')
            var daftarLokasi = lokasiBaru._store.choices.map(choice => choice.value)

            var result = daftarLokasi.filter(function(item) {
                var parts = item.split('#');

                if (parts.length === 3 && value.length === 3) {
                    var cleanPart1 = cleanString(parts[1].toLowerCase());
                    var cleanPart2 = cleanString(parts[2].toLowerCase());
                    var cleanValuePart1 = cleanString(value[1].toLowerCase());
                    var cleanValuePart2 = cleanString(value[2].toLowerCase());

                    return cleanValuePart1 === cleanPart1 && cleanValuePart2 === cleanPart2;
                }
                return false;
            });

            if (result.length > 0) {
                lokasiBaru.removeActiveItems();
                lokasiBaru.setChoiceByValue(result);
            }
        })

        function cleanString(str) {
            return str.replace(/\b(KAB|KEC|DS|KOTA)\.\s*/gi, '').trim();
        }

        function ambilLokasi() {
            let server = $('#server').val()

            $.get('/master/migrasi_upk/server/' + server, function(result) {
                if (result.success) {
                    inputLokasi.clearStore();

                    let dataSet = [{
                        value: "",
                        label: "--",
                        selected: true
                    }]
                    let data = result.data
                    data.forEach((item, i) => {
                        var id = item.id.toString()
                        id = id.padStart(3, "0")

                        dataSet.push({
                            value: item.id + '#' + item.nama_kab + '#' + item.nama_kec,
                            label: id + '. ' + item.nama_kab + ', ' + item.nama_kec,
                            selected: false
                        })
                    })

                    inputLokasi.setChoices(
                        dataSet,
                        'value',
                        'label',
                        false
                    );
                }
            })
        }

        ambilLokasi()

        $(document).on('click', '#BtnMigrasi', function(e) {
            e.preventDefault();

            var value = $('#lokasi').val().split('#')

            Swal.fire({
                icon: 'info',
                title: "Migrasi Lokasi " + value[0],
                showDenyButton: true,
                confirmButtonText: "Mulai",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#FormMigrasi').submit()
                }
            });
        })
    </script>
@endsection
