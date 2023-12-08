@php
    use App\Utils\Tanggal;
@endphp

@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="font-weight-normal mt-3">
                <div class="row">
                    <span class="col-sm-6">Laba/Rugi Tahun {{ Tanggal::tahun($tgl_kondisi) }}</span>
                    <span class="col-sm-6 text-end">Rp. {{ number_format($surplus, 2) }}</span>
                </div>
            </h4>
        </div>
    </div>
@endsection
