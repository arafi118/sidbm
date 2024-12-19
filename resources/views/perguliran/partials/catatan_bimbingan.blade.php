@php
    use App\Utils\Tanggal;
@endphp

<ol class="list-group list-group-numbered">
    @foreach ($data_catatan as $catatan)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">
                    {{ $data_user[$catatan['user']] }}
                    <span class="badge text-bg-info rounded-pill">
                        {{ Tanggal::tglIndo($catatan['tanggal']) }}
                    </span>
                </div>
                {!! $catatan['catatan'] !!}
            </div>
            <span class="badge text-bg-danger text-white pointer delete-catatan" data-id="{{ $loop->iteration }}">
                <i class="fas fa-trash"></i>
            </span>
        </li>
    @endforeach
</ol>
