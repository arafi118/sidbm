@php
    use App\Models\Wilayah;

    $wilayah = Wilayah::whereRaw('LENGTH(kode) = 2')
        ->with('kab', 'kab.kec')
        ->orderBy('nama', 'ASC')
        ->get();

    function active($curent, ...$_url)
    {
        $jumlah_url = count(request()->segments());
        $url = request()->segment($jumlah_url);

        if ($curent == $url) {
            return 'active bg-gradient-info';
        }

        if (in_array($url, $_url)) {
            return 'active';
        }

        if (in_array(request()->segment($jumlah_url - 1), $_url)) {
            return 'active';
        }

        return '';
    }

@endphp

<nav class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand text-center" href="/master">
            <span class="ms-1 font-weight-bold text-white" id="nama_lembaga_sort">
                Admin Page
            </span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mb-2 mt-0">
                <a href="#" class="nav-link text-white">
                    <img src="https://w7.pngwing.com/pngs/326/629/png-transparent-desktop-pc-pc-computer-calculator-icon.png"
                        class="avatar" id="profil_avatar">
                    <span class="nav-link-text ms-2 ps-1 nama_user">{{ Session::get('admin') }}</span>
                </a>
            </li>
            <hr class="horizontal light mt-0">
            <li class="nav-item">
                <a class="nav-link text-white {{ active('master') }}" href="/master">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ active('laporan') }}" href="/master/laporan">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">insert_drive_file</i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>


            <li class="nav-item mt-3">
                <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">Master Data</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ active('users') }}" href="/master/users">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">note_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Daftar User</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#MenuInvoice"
                    class="nav-link text-white {{ active('', 'buat_invoice', 'paid', 'unpaid') }}"
                    aria-controls="MenuInvoice" role="button" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">assessment</i>
                    </div>
                    <span class="nav-link-text ms-1">Invoice</span>
                </a>
                <div class="collapse" id="MenuInvoice">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ active('buat_invoice') }}" href="/master/buat_invoice">
                                <span class="sidenav-mini-icon"> BB </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Buat Baru </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ active('unpaid') }}" href="/master/unpaid">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Invoice Unpaid </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ active('paid') }}" href="/master/paid">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Invoice Paid </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ active('menu') }}" href="/master/menu">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dns</i>
                    </div>
                    <span class="nav-link-text ms-1">Menu</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
