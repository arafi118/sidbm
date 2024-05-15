<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/icon/favicon.png">
    <link rel="icon" type="image/png" href="/assets/img/icon/favicon.png">
    <title>
        SIDBM &mdash; Jembatan Akuntabilitas Bumdesma
    </title>
    <meta name="keywords" content="">
    <meta name="description" content="Sistem Informasi Dana Bergulir Masyarakat &mdash; Siap Audit Kapanpun Siapapun">

    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <link id="pagestyle" href="/assets/css/material-dashboard.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        .swal2-container {
            height: unset !important;
        }
    </style>
</head>

<body>
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100"
            style="background-image: url('https://images.unsplash.com/photo-1484417894907-623942c8ee29?auto=format&fit=crop&q=80&w=1932&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                                    <div class="text-center">
                                        Login Administrator Aplikasi
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" method="post" action="/master/login" class="text-start">
                                    @csrf

                                    <div
                                        class="input-group input-group-outline my-3 @error('gmail') is-invalid @enderror">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="gmail" id="gmail" autocomplete="off"
                                            class="form-control">
                                    </div>
                                    <div
                                        class="input-group input-group-outline mb-3 @error('password') is-invalid @enderror">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">
                                            Sign in
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/material-dashboard.min.js"></script>
    <script src="/assets/js/plugins/sweetalert.min.js"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function Toastr(icon, text) {
            font = "1.2rem Nimrod MT";

            canvas = document.createElement("canvas");
            context = canvas.getContext("2d");
            context.font = font;
            width = context.measureText(text).width;
            formattedWidth = Math.ceil(width) + 100;

            Toast.fire({
                icon: icon,
                title: text,
                width: formattedWidth
            })
        }
    </script>
    @if (session('pesan'))
        <script>
            Toastr('success', "{{ session('pesan') }}")
        </script>
    @endif
    @if (session('error'))
        <script>
            Toastr('error', "{{ session('error') }}")
        </script>
    @endif
</body>

</html>
