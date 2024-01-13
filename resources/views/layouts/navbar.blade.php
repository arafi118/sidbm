@php
    use App\Models\TandaTanganLaporan;
    $ttd = TandaTanganLaporan::where([['lokasi', Session::get('lokasi')]])->first();

    $tanggal = false;
    if ($ttd) {
        $str = strpos($ttd->tanda_tangan_pelaporan, '{tanggal}');

        if ($str !== false) {
            $tanggal = true;
        }
    }

    if (!$tanggal) {
        $jumlah += 1;
    }
@endphp

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
                @if (Session::get('angsuran') == true)
                    <div class="input-group input-group-outline">
                        <label class="form-label">Cari Kelompok</label>
                        @if (Request::get('pinkel'))
                            <input type="text" id="cariKelompok" name="cariKelompok" class="form-control"
                                value="{{ $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] [' . $pinkel->kelompok->ketua . ']' }}">
                        @else
                            <input type="text" id="cariKelompok" name="cariKelompok" class="form-control">
                        @endif
                    </div>
                @endif
            </div>
            <ul class="navbar-nav justify-content-end align-items-center">
                <li class="nav-item">
                    <a href="#" class="nav-link text-body p-0 position-relative" target="_blank"
                        id="btnLaporanPelunasan" data-bs-toggle="tooltip" data-bs-placement="top" title="Reminder"
                        data-container="body" data-animation="true">
                        <i class="material-icons me-sm-1">
                            notifications_active
                        </i>
                    </a>
                </li>
                <li class="nav-item dropdown ps-3" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="TS dan Invoice" data-container="body" data-animation="true">
                    <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-icons cursor-pointer me-sm-1">
                            chat_bubble
                        </i>
                        @if ($jumlah > 0)
                            <span
                                class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2">
                                <span class="small">{{ $jumlah }}</span>
                                <span class="visually-hidden">Notifikasi</span>
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            @if ($jumlah > 0)
                                @foreach ($inv as $in)
                                    <a class="dropdown-item border-radius-md"
                                        href="/pelaporan/invoice/{{ $in->idv }}" target="_blank">
                                        <div class="d-flex align-items-center py-1">
                                            <span class="material-icons">event</span>
                                            <div class="ms-2">
                                                <h6 class="text-sm font-weight-normal my-auto">
                                                    {{ $in->jp->nama_jp }}
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                            @if (!$tanggal)
                                <a class="dropdown-item border-radius-md" href="/pengaturan/ttd_pelaporan">
                                    <div class="d-flex align-items-center py-1">
                                        <span class="material-icons">date_range</span>
                                        <div class="ms-2">
                                            <h6 class="text-sm font-weight-normal my-auto">
                                                Tanggal pada laporan
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            @endif
                            <a class="dropdown-item border-radius-md" href="/pelaporan/ts" target="_blank">
                                <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">contact_phone</span>
                                    <div class="ms-2">
                                        <h6 class="text-sm font-weight-normal my-auto">
                                            Technical Support
                                        </h6>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item border-radius-md" href="/pelaporan/mou" target="_blank">
                                <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">library_books</span>
                                    <div class="ms-2">
                                        <h6 class="text-sm font-weight-normal my-auto">
                                            MoU
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item ps-3">
                    <a href="javascript:;" class="nav-link text-body p-0" id="logout" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Logout" data-container="body" data-animation="true">
                        <i class="material-icons cursor-pointer">
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

<form action="/pelaporan/preview" method="post" id="FormLaporanSisipan" target="_blank">
    @csrf

    <input type="hidden" name="type" id="type" value="pdf">
    <input type="hidden" name="tahun" id="tahun" value="{{ date('Y') }}">
    <input type="hidden" name="bulan" id="bulan" value="{{ date('m') }}">
    <input type="hidden" name="hari" id="hari" value="{{ date('d') }}">
    <input type="hidden" name="laporan" id="laporan" value="pelunasan">
    <input type="hidden" name="sub_laporan" id="sub_laporan" value="">
</form>
