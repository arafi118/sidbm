@extends('layouts.base')

@section('content')
    <div class="row mb-5">
        <div class="col-lg-3">
            <div class="card position-sticky top-10">
                <ul class="nav flex-column bg-white border-radius-lg p-3">
                    <li class="nav-item mb-2">
                        <b>Pengaturan</b>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#lembaga">
                            <i class="material-icons text-lg me-2">business</i>
                            <span class="text-sm">Identitas Lembaga</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#pengelola">
                            <i class="material-icons text-lg me-2">assignment_ind</i>
                            <span class="text-sm">Sebutan Pengelola</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#pinjaman">
                            <i class="material-icons text-lg me-2">equalizer</i>
                            <span class="text-sm">Sistem Pinjaman</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#asuransi">
                            <i class="material-icons text-lg me-2">account_balance_wallet</i>
                            <span class="text-sm">Pengaturan Asuransi</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#redaksi_spk">
                            <i class="material-icons text-lg me-2">description</i>
                            <span class="text-sm">Redaksi Dok. SPK</span>
                        </a>
                    </li>
                    <li class="nav-item pt-2">
                        <a class="nav-link text-dark d-flex" data-scroll="" href="#logo">
                            <i class="material-icons text-lg me-2">crop_original</i>
                            <span class="text-sm">Logo</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9 mt-lg-0 mt-4">
            <div class="card" id="lembaga">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
            <div class="card mt-4" id="pengelola">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
            <div class="card mt-4" id="pinjaman">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
            <div class="card mt-4" id="asuransi">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
            <div class="card mt-4" id="redaksi_spk">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
            <div class="card mt-4" id="logo">
                <div class="card-header">
                    <h5></h5>
                </div>
                <div class="card-body pt-0">
                    {{--  --}}
                </div>
            </div>
        </div>
    </div>
@endsection
