@php
    use App\Models\TandaTanganLaporan;

    $path = explode('/', Request::path());
    $show = ((in_array('perguliran', $path)
                ? true
                : in_array('detail', $path))
            ? true
            : in_array('kelompok', $path))
        ? true
        : false;

    $angsuran = false;
    if (in_array('jurnal_angsuran', Session::get('akses_menu'))) {
        $angsuran = true;
    }

    if (!in_array('jurnal_angsuran.cari_kelompok', Session::get('tombol')) && !in_array('jurnal_angsuran', $path)) {
        $angsuran = false;
    }
@endphp

<nav class="navbar navbar-main navbar-expand-lg px-0 border-radius-xl {{ $config['navbarFixed'] }}" id="navbarBlur"
    data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link text-body p-0" onclick="navbarMinimize(this)" checked="true">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </div>
        <div class="ms-md-3 pe-md-3 d-flex align-items-center w-100">
            @if ($angsuran && !$show)
                <div class="input-group input-group-outline">
                    <label class="form-label">Angsuran Kelompok</label>
                    @if (Request::get('pinkel'))
                        <input type="text" id="cariKelompok" name="cariKelompok" class="form-control"
                            autocomplete="off"
                            value="{{ $pinkel->kelompok->nama_kelompok . ' [' . $pinkel->kelompok->d->nama_desa . '] [' . $pinkel->kelompok->ketua . ']' }}">
                    @else
                        <input type="text" id="cariKelompok" name="cariKelompok" class="form-control"
                            autocomplete="off">
                    @endif
                </div>
            @endif
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 justify-content-end" id="navbar">
            <ul class="navbar-nav justify-content-end align-items-center">
                @if ($angsuran && !$show)
                    <li class="nav-item">
                        <a href="#" class="nav-link text-body p-0 position-relative" id="btnScanKartu"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Scan Kartu Angsuran"
                            data-container="body" data-animation="true">
                            <i class="material-icons me-sm-1">
                                search
                            </i>
                        </a>
                    </li>
                @endif
                <li class="nav-item ps-3">
                    <a href="/download/panduan-transaksi-sidbm.xlsx" class="nav-link text-body p-0 position-relative"
                        target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Panduan Transaksi"
                        data-container="body" data-animation="true">
                        <i class="material-icons me-sm-1">
                            tips_and_updates
                        </i>
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a href="/download/profil-sidbm-baru.ppt" class="nav-link text-body p-0 position-relative"
                        target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Profil Sidbm"
                        data-container="body" data-animation="true">
                        <i class="material-icons me-sm-1">
                            info_outline
                        </i>
                    </a>
                </li>
                <li class="nav-item ps-3">
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
                            <a class="dropdown-item border-radius-md" href="/pelaporan/ba_bumdesma" target="_blank">
                                <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">attach_file</span>
                                    <div class="ms-2">
                                        <h6 class="text-sm font-weight-normal my-auto">
                                            BA Pergantian Pelaporan
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

<div class="modal fade" id="scanQrCode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="scanQrCodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="scanQrCodeLabel">
                    Scan Kartu Angsuran
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-info" id="stopScan">Stop</button>
                <button type="button" class="btn btn-sm btn-danger" id="scanQrCodeClose">Close</button>
            </div>
        </div>
    </div>
</div>
