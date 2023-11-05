@php

    function active($curent, ...$_url)
    {
        $jumlah_url = count(request()->segments());
        $url = request()->segment($jumlah_url);

        if ($curent == $url) {
            return 'active';
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

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main" data-color="success">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand text-center" href="/dashboard">
            <span class="ms-1 font-weight-bold text-white" id="nama_lembaga_sort">
                {{ Session::get('nama_lembaga') }}
            </span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mb-2 mt-0">
                <a href="/profil" class="nav-link text-white">
                    <img src="{{ asset('storage/profil/' . Session::get('foto')) }}" class="avatar" id="profil_avatar">
                    <span class="nav-link-text ms-2 ps-1 nama_user">{{ Session::get('nama') }}</span>
                </a>
            </li>
            <hr class="horizontal light mt-0">
            <li class="nav-item nav-item-link">
                <a class="nav-link text-white {{ active('dashboard', 'dashboard') }}" href="/dashboard">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#pengaturanMenu"
                    class="nav-link text-white {{ active('', 'sop', 'coa', 'ttd_pelaporan', 'ttd_spk') }}"
                    aria-controls="pengaturanMenu" role="button" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">settings_applications</i>
                    </div>
                    <span class="nav-link-text ms-1">Pengaturan</span>
                </a>
                <div class="collapse" id="pengaturanMenu">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active('sop') }}">
                            <a class="nav-link text-white {{ active('sop') }}" href="/pengaturan/sop">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Personalisasi SOP </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('coa') }}">
                            <a class="nav-link text-white {{ active('coa') }}" href="/pengaturan/coa">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Cart Of Account </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('ttd_pelaporan') }}">
                            <a class="nav-link text-white {{ active('ttd_pelaporan') }}"
                                href="/pengaturan/ttd_pelaporan">
                                <span class="sidenav-mini-icon">
                                    <i class="material-icons-round opacity-10">border_color</i>
                                </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Ttd Pelaporan </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('ttd_spk') }}">
                            <a class="nav-link text-white {{ active('ttd_spk') }}" href="/pengaturan/ttd_spk">
                                <span class="sidenav-mini-icon">
                                    <i class="material-icons-round opacity-10">border_color</i>
                                </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Ttd SPK </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('invoice') }}">
                            <a class="nav-link text-white {{ active('invoice') }}" href="/pengaturan/invoice">
                                <span class="sidenav-mini-icon"> IN </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Invoice </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">Master Data</h6>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#Database"
                    class="nav-link text-white
                    {{ active('', 'desa', 'kelompok', 'register_kelompok', 'penduduk', 'register_penduduk') }}"
                    aria-controls="Database" role="button" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">archive</i>
                    </div>
                    <span class="nav-link-text ms-1">Basis Data</span>
                </a>
                <div class="collapse " id="Database">
                    <ul class="nav ">
                        <li class="nav-item {{ active('desa') }}">
                            <a class="nav-link text-white {{ active('desa') }}" href="/database/desa">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Data Desa </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white {{ active('', 'kelompok', 'register_kelompok') }}"
                                data-bs-toggle="collapse" aria-expanded="false" href="#dataKelompok">
                                <span class="sidenav-mini-icon"> K </span>
                                <span class="sidenav-normal ms-2  ps-1"> Kelompok <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="dataKelompok">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ active('register_kelompok') }}"
                                            href="/database/kelompok/register_kelompok">
                                            <span class="sidenav-mini-icon"> RK </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Register Kelompok </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ active('kelompok') }}"
                                            href="/database/kelompok">
                                            <span class="sidenav-mini-icon"> DK </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Data Kelompok </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white {{ active('', 'penduduk', 'register_penduduk') }}"
                                data-bs-toggle="collapse" aria-expanded="false" href="#dataPenduduk">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Penduduk <b class="caret"></b></span>
                            </a>
                            <div class="collapse " id="dataPenduduk">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ active('register_penduduk') }}"
                                            href="/database/penduduk/register_penduduk">
                                            <span class="sidenav-mini-icon"> RP </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Register Penduduk </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ active('penduduk') }}"
                                            href="/database/penduduk">
                                            <span class="sidenav-mini-icon"> DP </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Data Penduduk </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-link">
                <a class="nav-link text-white {{ active('register_proposal') }}" href="/register_proposal">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">note_add</i>
                    </div>
                    <span class="nav-link-text ms-1">Register Proposal</span>
                </a>
            </li>
            <li class="nav-item nav-item-link">
                <a class="nav-link text-white {{ active('perguliran', 'detail', 'lunas') }}" href="/perguliran">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">event_note</i>
                    </div>
                    <span class="nav-link-text ms-1">Tahapan Perguliran</span>
                </a>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#MenuTransaksi"
                    class="nav-link text-white {{ active('', 'jurnal_umum', 'jurnal_angsuran', 'ebudgeting', 'tutup_buku') }}"
                    aria-controls="MenuTransaksi" role="button" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">assessment</i>
                    </div>
                    <span class="nav-link-text ms-1">Transaksi</span>
                </a>
                <div class="collapse" id="MenuTransaksi">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item {{ active('jurnal_umum') }}">
                            <a class="nav-link text-white {{ active('jurnal_umum') }}" href="/transaksi/jurnal_umum">
                                <span class="sidenav-mini-icon"> JU </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Jurnal Umum </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('jurnal_angsuran') }}">
                            <a class="nav-link text-white {{ active('jurnal_angsuran') }}"
                                href="/transaksi/jurnal_angsuran">
                                <span class="sidenav-mini-icon"> JA </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Jurnal Angsuran </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('ebudgeting') }}">
                            <a class="nav-link text-white {{ active('ebudgeting') }}" href="/transaksi/ebudgeting">
                                <span class="sidenav-mini-icon"> EB </span>
                                <span class="sidenav-normal  ms-2  ps-1"> E - Budgeting </span>
                            </a>
                        </li>
                        <li class="nav-item {{ active('tutup_buku') }}">
                            <a class="nav-link text-white {{ active('tutup_buku') }}" href="/transaksi/tutup_buku">
                                <span class="sidenav-mini-icon"> TB </span>
                                <span class="sidenav-normal  ms-2  ps-1"> Tutup Buku </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">Laporan</h6>
            </li>
            <li class="nav-item nav-item-link">
                <a class="nav-link text-white {{ active('pelaporan') }}" href="/pelaporan">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">insert_drive_file</i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
