@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <embed src="/pelaporan/invoice/{{ $inv->idv }}" type="application/pdf" width="100%" height="600px" />

            <div class="d-flex justify-content-end mt-3">
                <a href="/pengaturan/invoice" class="btn btn-sm btn-info mb-0">Kembali</a>
            </div>
        </div>
    </div>
@endsection
