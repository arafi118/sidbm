<nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky"
    id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-3 text-dark" href="/dashboard">
                        <i class="material-icons">home</i>
                    </a>
                </li>
                @for ($i = 1; $i <= count(request()->segments()); $i++)
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                        {{ ucwords(str_replace('_', ' ', Request::segment($i))) }}
                    </li>
                @endfor
            </ol>
            <h6 class="font-weight-bolder mb-0">
                @if (Request::segment(count(request()->segments()) - 1) == 'detail' ||
                        Request::segment(count(request()->segments()) - 1) == 'lunas')
                    Loan ID. {{ ucwords(str_replace('_', ' ', Request::segment(count(request()->segments())))) }}
                @else
                    {{ ucwords(str_replace('_', ' ', Request::segment(count(request()->segments())))) }}
                @endif
            </h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link text-body p-0" onclick="navbarMinimize(this)">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-between" id="navbar">
            <div class="ms-md-3 pe-md-3 d-flex align-items-center w-100">
                <div class="input-group input-group-outline">
                    <label class="form-label">Cari Kelompok</label>
                    @if (Request::get('pinkel'))
                        <input type="text" id="cariKelompok" name="cariKelompok" class="form-control"
                            value="{{ $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] [' . $pinkel->kelompok->ketua . ']' }}">
                    @else
                        <input type="text" id="cariKelompok" name="cariKelompok" class="form-control">
                    @endif
                </div>
            </div>
            <ul class="navbar-nav justify-content-end align-items-center">
                <li class="nav-item">
                    <a href="/pelaporan/mou" class="nav-link text-body p-0 position-relative" target="_blank">
                        <i class="material-icons me-sm-1">
                            library_books
                        </i>
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a href="/pages/authentication/signin/illustration.html"
                        class="nav-link text-body p-0 position-relative" target="_blank">
                        <i class="material-icons me-sm-1">
                            chat_bubble
                        </i>
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="material-icons fixed-plugin-button-nav cursor-pointer">
                            settings
                        </i>
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a href="javascript:;" class="nav-link text-body p-0" id="logout">
                        <i class="material-icons fixed-plugin-button-nav cursor-pointer">
                            exit_to_app
                        </i>
                    </a>
                </li>
                <li class="nav-item d-xl-none d-flex align-items-center ps-3">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
