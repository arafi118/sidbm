@extends('layouts.base')

@section('content')
    @php
        $config = json_decode(Session::get('config'), true);
        $sidebarColor = $config['sidebarColor'] ?? 'primary';

        $titles = [
            1 => 'Tarik & Setor Bank',
            2 => 'Bunga, Pajak & Adm Bank',
            3 => 'Pembelian Aset Tetap (ATI)',
            4 => 'Aset Tak Berwujud',
            5 => 'Laba Masyarakat/Desa',
            6 => 'Pinjaman Pihak ke-3',
            7 => 'Penyertaan Modal Desa',
            8 => 'Pajak PPh',
            9 => 'Beban Penyusutan',
            10 => 'Beban Amortisasi',
            11 => 'Tarik Kas Kecil',
            12 => 'Beban Operasional',
            13 => 'Investasi Unit Usaha',
            14 => 'Penghapusan ATI',
            15 => 'Penyisihan Piutang (CKP)',
            16 => 'Utang Bonus Prestasi',
            17 => 'Pembelian Secara Tempo',
        ];

        // Reference Data for Guides
        $bankAccounts = [
            '1.1.01.03' => 'Kas di Bank Operasional',
            '1.1.01.04' => 'Kas di Bank SPP',
            '1.1.01.05' => 'Kas di Bank Bumdesma',
        ];

        $bebanAccounts = [
            '5.1.03.02' => 'Beban Listrik',
            '5.1.03.03' => 'Beban Internet',
            '5.1.01.02' => 'Beban Gaji Pegawai',
            '5.1.03.01' => 'Beban Administrasi & Umum',
            '5.1.03.04' => 'Beban Pemeliharaan Aset',
            '5.1.06.01' => 'Beban Perjalanan Dinas',
        ];
    @endphp

    <div class="row mb-5">
        {{-- Navigation Sidebar --}}
        <div class="col-lg-3">
            <div class="card position-sticky top-10 sticky-sidebar-card">
                <ul class="nav flex-column flex-nowrap bg-white border-radius-lg p-3" id="GuideSidebar">
                    <li class="nav-item mb-2">
                        <b>Daftar Panduan</b>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#jurnal-umum">
                            <i class="material-icons text-lg me-2">history_edu</i>
                            <span class="text-sm">Jurnal Umum</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#pinjaman">
                            <i class="material-icons text-lg me-2">payments</i>
                            <span class="text-sm">Pinjaman</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2 mt-2 mb-2">
                        <b>Daftar Transaksi</b>
                    </li>
                    @foreach ($titles as $i => $title)
                        <li class="nav-item pt-2">
                            <a class="nav-link text-dark d-flex sidebar-link" data-scroll="" href="#trx-{{ $i }}">
                                <span class="text-xs font-weight-bold me-2">{{ $i }}.</span>
                                <span class="text-sm">{{ $title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="col-lg-9 mt-lg-0 mt-4">
            <div class="card mb-4" id="jurnal-umum">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Panduan Transaksi Jurnal Umum</h5>
                </div>
                <hr class="horizontal dark">
                <div class="card-body pt-0">
                    <div class="alert alert-info border-radius-lg text-white text-sm" role="alert">
                        <strong>Perhatian:</strong> Simbol <span class="badge badge-sm bg-white text-dark">...</span> atau <span class="badge badge-sm bg-white text-dark">xx.xx</span> berarti nilai tersebut harus <strong>disesuaikan</strong> dengan kondisi sebenarnya (seperti Nama Bank, Akun Beban, atau Tanggal).
                    </div>
                </div>
            </div>

            <div id="GuideList">
                @foreach ($titles as $index => $title)
                    <div class="card mb-4 guide-section" id="trx-{{ $index }}">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ $index }}. {{ $title }}</h6>
                            <span class="badge badge-sm bg-gradient-{{ $sidebarColor }} opacity-7 text-xxs">Jurnal Umum</span>
                        </div>
                        <hr class="horizontal dark">
                        <div class="card-body pt-0">
                            @if ($index == 1)
                                <div class="row">
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Tarik Bank', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.04 (Bank SPP)', 'simpan' => '1.1.01.01 (Kas Tunai)', 'ket' => 'Tarik dari Bank .....', 'note' => 'Sesuaikan Sumber Dana dengan Nama Bank tujuan penarikan.'])</div>
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Setor Bank', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas Tunai)', 'simpan' => '1.1.01.04 (Bank SPP)', 'ket' => 'Setor harian ke Bank .....', 'note' => 'Sesuaikan Disimpan Ke dengan Nama Bank tujuan setoran.'])</div>
                                </div>
                                <div class="bg-gray-100 border-radius-lg p-2 mt-2">
                                    <p class="text-xs font-weight-bold mb-1">Referensi Rekening Bank:</p>
                                    @foreach($bankAccounts as $code => $name)
                                        <span class="badge bg-white text-dark text-xxs border mb-1">{{ $code }} - {{ $name }}</span>
                                    @endforeach
                                </div>
                            @elseif($index == 2)
                                <div class="row">
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Bunga Bank', 'jt' => 'Aset Masuk', 'sumber' => '4.2.01.01 (Pend. Bunga)', 'simpan' => '1.1.01.03 (Bank Operasional)', 'ket' => 'Bunga Bank .....', 'note' => 'Pilih Bank yang menerima bunga.'])</div>
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Pajak/Adm Bank', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.03 (Bank Operasional)', 'simpan' => '5.3.01.xx (Beban Pajak/Adm)', 'ket' => 'Pajak/Adm Bank .....', 'note' => 'Pilih Bank asal biaya dan pilih kode akun beban yang sesuai.'])</div>
                                </div>
                            @elseif($index == 3)
                                @include('panduan.partials._mock_form', ['sub' => 'Pembelian Aset', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas Tunai)', 'simpan' => '1.2.01.xx (Pilih Akun Aset)', 'ket' => 'Beli (Nama Barang)', 'ati' => true, 'note' => 'Sesuaikan Akun Aset (Tanah/Gedung/Kendaraan/Inventaris).'])
                            @elseif($index == 4)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Pendirian/Lisensi', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas Umum)', 'simpan' => '1.2.03.01 (Biaya Pendirian)', 'ket' => 'Bayar Akta/Lisensi/Sewa...', 'relasi' => 'Notaris/Pihak Ke-3'])</div>
                                 </div>
                            @elseif($index == 5)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Laba Masyarakat', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.01 (Kas Tunai)', 'simpan' => '3.2.02.01 (Laba Dana Sosial)', 'ket' => 'Penyaluran dana sosial masyarakat ke...', 'relasi' => 'Nama Kelompok/Masyarakat'])</div>
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Laba Desa', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.01 (Kas Tunai)', 'simpan' => '3.2.02.02 (Laba Dana Pembangunan)', 'ket' => 'Penyaluran PAD Desa ke...', 'relasi' => 'Pemerintah Desa (Nama Desa)'])</div>
                                </div>
                            @elseif($index == 6)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Pinjaman Pihak-3', 'jt' => 'Aset Masuk', 'sumber' => '2.1.01.xx (Pilih Pihak Ke-3)', 'simpan' => '1.1.01.03 (Bank Operasional)', 'ket' => 'Penerimaan pinjaman dari...', 'relasi' => 'Nama Kreditur/Pihak Ke-3'])</div>
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Bayar Pinjaman', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.03 (Bank Operasional)', 'simpan' => '2.1.01.xx (Pilih Pihak Ke-3)', 'ket' => 'Pembayaran utang ke...', 'relasi' => 'Nama Kreditur/Pihak Ke-3'])</div>
                                </div>
                            @elseif($index == 7)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Penyertaan Modal', 'jt' => 'Aset Masuk', 'sumber' => '3.1.01.xx (Pilih Desa)', 'simpan' => '1.1.01.03 (Bank Operasional)', 'ket' => 'Setoran modal dari Desa...', 'relasi' => 'Pemerintah Desa (Nama Desa)'])</div>
                                </div>
                            @elseif($index == 8)
                                <div class="row">
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Taksiran PPh', 'jt' => 'Aset Keluar', 'sumber' => '2.1.03.01 (Utang Pajak)', 'simpan' => '5.4.01.01 (Taksiran PPh)', 'ket' => 'Taksiran pajak bulan ini'])</div>
                                    <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Setor Pajak', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '2.1.03.01 (Utang Pajak)', 'ket' => 'Setor PPh ke Kas Negara'])</div>
                                </div>
                            @elseif($index == 9)
                                @include('panduan.partials._mock_form', ['sub' => 'Beban Penyusutan', 'jt' => 'Aset Keluar', 'sumber' => '1.2.02.xx (Akumulasi Penyusutan)', 'simpan' => '5.1.07.xx (Beban Penyusutan)', 'ket' => 'Penyusutan Gedung/Inventaris Bulan ...'])
                            @elseif($index == 10)
                                @include('panduan.partials._mock_form', ['sub' => 'Beban Amortisasi', 'jt' => 'Aset Keluar', 'sumber' => '1.2.04.xx (Akumulasi Amortisasi)', 'simpan' => '5.1.07.xx (Beban Amortisasi)', 'ket' => 'Amortisasi Sewa/Lisensi Bulan ...'])
                            @elseif($index == 11)
                                @include('panduan.partials._mock_form', ['sub' => 'Antar Kas/Bank', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '1.1.01.02 (Kas Kecil)', 'ket' => 'Isi ulang Kas Kecil dari Kas Tunai'])
                            @elseif($index == 12)
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <p class="text-xs mb-3"><b>Beban Operasional</b> (Gaji, ATK, Listrik, Telkom, Perjalanan, dll):</p>
                                        @include('panduan.partials._mock_form', ['sub' => 'Beban Operasional', 'jt' => 'Aset Keluar', 'color' => 'danger', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '5.1.02.xx (Pilih Akun Beban)', 'ket' => 'Bayar (Nama Beban) bulan ...', 'relasi' => 'Pihak Terkait (Supplier/Karyawan)'])
                                    </div>
                                </div>
                            @elseif($index == 13)
                                @include('panduan.partials._mock_form', ['sub' => 'Investasi Unit', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '1.1.06.01 (Investasi unit Usaha)', 'ket' => 'Setoran investasi ke unit usaha ...', 'relasi' => 'Nama Unit Usaha'])
                            @elseif($index == 14)
                                @include('panduan.partials._mock_form', ['sub' => 'Hapus Aset', 'jt' => 'Aset Keluar', 'sumber' => '1.2.02.03 (Akumulasi)', 'simpan' => '5.3.02.01 (Beban Penghapusan)', 'ket' => 'Penghapusan Aset ...', 'hapus_ati' => true])
                            @elseif($index == 15)
                                @include('panduan.partials._mock_form', ['sub' => 'Penyisihan CKP', 'jt' => 'Aset Keluar', 'sumber' => '1.1.04.01 (Cadangan Kerugian Piutang)', 'simpan' => '5.1.07.01 (Beban Penyisihan Piutang)', 'ket' => 'Beban penyisihan CKP'])
                            @elseif($index == 16)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Taksiran Bonus', 'jt' => 'Aset Keluar', 'sumber' => '2.1.02.04 (Utang Bonus)', 'simpan' => '5.1.02.05 (Bonus Prestasi)', 'ket' => 'Taksiran bonus tahun ...'])</div>
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Bayar Bonus', 'jt' => 'Aset Keluar', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '2.1.02.04 (Utang Bonus)', 'ket' => 'Pembayaran bonus', 'relasi' => 'Nama Pengurus/Karyawan'])</div>
                                </div>
                            @elseif($index == 17)
                                <div class="row">
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Bayar DP', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '1.2.05.01 (Konstruksi Dalam Pengerjaan)', 'ket' => 'Uang muka pembelian ...', 'relasi' => 'Nama Toko/Supplier'])</div>
                                     <div class="col-12 mb-3">@include('panduan.partials._mock_form', ['sub' => 'Pelunasan', 'jt' => 'Pemindahan Saldo', 'sumber' => '1.1.01.01 (Kas)', 'simpan' => '1.2.05.01 (Konstruksi Dalam Pengerjaan)', 'ket' => 'Pelunasan barang tempo', 'relasi' => 'Nama Toko/Supplier'])</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="card mb-4 guide-section" id="pinjaman">
                    <div class="card-header pb-0 border-start border-primary border-5">
                        <h5 class="mb-0 ms-2">Jurnal Pinjaman (Angsuran & Pencairan)</h5>
                    </div>
                    <hr class="horizontal dark">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="bg-light border-radius-lg p-3">
                                    <h6 class="text-success text-sm">Pencairan (Menu: Transaksi > Pencairan)</h6>
                                    <p class="text-xs text-secondary">Pastikan Saldo Kas cukup. Jika tidak, lakukan <b>Tarik Bank</b> terlebih dahulu (Trx 1).</p>
                                    <ul class="text-xs mb-0">
                                        <li>Cari Kelompok/Pemanfaat</li>
                                        <li>Klik Cairkan</li>
                                        <li>Cetak Kuitansi</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="bg-light border-radius-lg p-3">
                                    <h6 class="text-info text-sm">Angsuran (Menu: Transaksi > Jurnal Angsuran)</h6>
                                    <p class="text-xs text-secondary">Digunakan untuk menerima setoran bulanan kelompok.</p>
                                    <ul class="text-xs mb-0">
                                        <li>Pilih Rekening Kas Tujuan Setor</li>
                                        <li>Input Nominal Pokok & Jasa</li>
                                        <li>Klik Simpan & Cetak</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize PerfectScrollbar if the plugin exists
            if (typeof PerfectScrollbar !== 'undefined') {
                const ps = new PerfectScrollbar('#GuideSidebar', {
                    wheelSpeed: 2,
                    wheelPropagation: false,
                    minScrollbarLength: 20
                });
            }

            // Multi-engine Smooth Scroll (Detects native or plugin scroll)
            $('[data-scroll]').on('click', function(e) {
                var hash = this.hash;
                if (hash && $(hash).length) {
                    e.preventDefault();
                    var $target = $(hash);
                    var container = document.querySelector('.main-content');
                    var targetOffset = $target.offset().top - (container ? $(container).offset().top : 0) - 120;

                    // 1. Try smooth-scrollbar plugin (common in this theme)
                    if (window.Scrollbar && container) {
                        var scrollbar = Scrollbar.get(container);
                        if (scrollbar) {
                            scrollbar.scrollTo(0, scrollbar.scrollTop + targetOffset, 600);
                            return;
                        }
                    }

                    // 2. Fallback to jQuery animate for .main-content
                    if (container && container.scrollHeight > container.clientHeight) {
                        $(container).animate({ scrollTop: $(container).scrollTop() + targetOffset }, 400);
                    } else {
                        // 3. Fallback to native html,body
                        $('html, body').animate({ scrollTop: $(hash).offset().top - 120 }, 400);
                    }
                }
            });

            // Scroll Spy
            $(window).scroll(function() {
                var scrollDistance = $(window).scrollTop() + 150;

                $('.guide-section, #jurnal-umum, #pinjaman').each(function(i) {
                    if ($(this).position().top <= scrollDistance) {
                        $('.sidebar-link.active, #GuideSidebar .nav-link.active').removeClass('active bg-light font-weight-bold');
                        var id = $(this).attr('id');
                        if (id) {
                            $('[href="#' + id + '"]').addClass('active bg-light font-weight-bold');
                        }
                    }
                });
            }).scroll();
        });
    </script>
    <style>
        .guide-section {
            scroll-margin-top: 120px;
        }
        
        .ps__scrollbar-y-rail {
            z-index: 1060 !important;
        }

        /* Disable sticky on mobile */
        @media (max-width: 991.98px) {
            .sticky-sidebar-card {
                position: relative !important;
                top: 0 !important;
                max-height: none !important;
                overflow: visible !important;
                margin-bottom: 2rem;
            }
            #GuideSidebar {
                height: auto !important;
                overflow: visible !important;
            }
        }

        /* Desktop Sticky & Scroll */
        @media (min-width: 992px) {
            .sticky-sidebar-card {
                max-height: calc(100vh - 120px);
                overflow-y: auto;
                overflow-x: hidden;
                z-index: 100; /* Lower than main navbar */
            }
        }
    </style>
@endsection
