@php
    $thn_awal = explode('-', $kec->tgl_pakai)[0];
@endphp

@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/pengaturan/proyeksi_pendapatan_jasa" method="post" target="_blank">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <div class="my-2">
                            <label class="form-label" for="tahun">Tahunan</label>
                            <select class="form-control" name="tahun" id="tahun">
                                <option value="">---</option>
                                @for ($i = $thn_awal; $i <= date('Y') + 1; $i++)
                                    <option {{ $i == date('Y') ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <small class="text-danger" id="msg_tahun"></small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" name="jenis" value="pdf" class="btn btn-sm btn-github">Preview</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        new Choices($('select#tahun')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
    </script>
@endsection
