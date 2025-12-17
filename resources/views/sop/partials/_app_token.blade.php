@php
    $token1 = rand(1000, 9999);
    $token2 = rand(1000, 9999);
    $token3 = rand(1000, 9999);
    $token4 = date('ym');
@endphp

<form action="https://api-sidbm.siupk.net/api/generate-token/{{ $kec->id }}" method="put" id="FormAppToken">
    @csrf
    @method('PUT')

    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
        <h6 class="mb-0">
            ****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;{{ $token4 }}
        </h6>

        <input type="hidden" value="{{ $token1 }}-{{ $token2 }}-{{ $token3 }}-{{ $token4 }}"
            id="hidden-app-token" name="token">
        <span class="ms-auto text-dark cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-original-title="Salin" id="copy-token">
            <i class="fas fa-copy"></i>
        </span>
    </div>
</form>
