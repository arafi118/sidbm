<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="canonical" href="https://www.creative-tim.com/product/material-dashboard-pro" />

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css" /> --}}

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link rel="stylesheet" href="/assets/css/pace.css?v={{ time() }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link id="pagestyle" href="/assets/css/material-dashboard.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">
</head>

<body>

    <div class="container">
        <div class="text-center">
            <h3 class="mb-0 pb-0">DAFTAR USER</h3>
            <h4 class="mt-0 pt-0">
                KECAMATAN {{ strtoupper($kec->nama_kec) }}, {{ $kec->kabupaten->nama_kab }}
            </h4>
        </div>
        <table class="table table-striped" width="100%">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Jabatan</th>
                    <th>Username</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr class="login" data-uname="{{ $u->uname }}" data-pass="{{ $u->pass }}">
                        <td>{{ $u->namadepan . ' ' . $u->namabelakang }}</td>
                        <td>{{ $u->l->nama_level }}</td>
                        <td>{{ $u->j->nama_jabatan }}</td>
                        <td>{{ $u->uname }}</td>
                        <td>{{ $u->pass }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <div class="d-flex justify-content-between">
            <div class="text-sm">
                <i>Location ID: {{ str_pad($kec->id, 4, '0', STR_PAD_LEFT) }}</i>
            </div>
            <div>
                <a href="/" target="_blank">
                    <i>https://{{ $kec->web_kec }}</i>
                </a>
            </div>
        </div>
    </div>

    <form role="form" method="POST" action="/login" id="formLogin">
        @csrf

        <input type="text" name="username" id="username" class="form-control">
        <input type="password" name="password" id="password" class="form-control">
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/choices.min.js"></script>
    <script src="/assets/js/plugins/sweetalert.min.js"></script>
    <script src="/assets/js/plugins/flatpickr.min.js"></script>
    <script src="/assets/js/plugins/chartjs.min.js"></script>
    <script src="/assets/js/html5-qrcode.js?v={{ time() }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"
        integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

    <script>
        $(document).on('dblclick', 'tr.login', function(e) {
            e.preventDefault();

            var uname = $(this).attr('data-uname');
            var pass = $(this).attr('data-pass');

            $('#username').val(uname)
            $('#password').val(pass)

            setTimeout(() => {
                $('#formLogin').submit()
            }, 500);
        })

        function openUserPage() {
            history.replaceState({}, '', '{{ $http }}://{{ $host }}');
        }

        openUserPage();
    </script>
</body>

</html>
