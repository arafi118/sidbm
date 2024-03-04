<div class="card card-body p-2 pb-0 mb-4">
    <div class="row">
        <div class="col-md-3 d-grid">
            <button type="button" data-action="/transaksi/dokumen/struk/{{ $idtp }}"
                class="btn btn-linkedin btn-tooltip btn-sm btn-link mb-2" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Struk" data-container="body" data-animation="true">
                <span class="btn-inner--icon">
                    <i class="fas fa-file"></i> Struk
                </span>
            </button>
        </div>
        <div class="col-md-3 d-grid">
            <button type="button" data-action="/transaksi/dokumen/struk_matrix/{{ $idtp }}"
                class="btn btn-linkedin btn-tooltip btn-sm btn-link mb-2" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Struk Dot Matrix" data-container="body" data-animation="true">
                <span class="btn-inner--icon">
                    <i class="fas fa-file"></i> Struk Dot Matrix
                </span>
            </button>
        </div>
        <div class="col-md-3 d-grid">
            <button type="button" data-action="/transaksi/dokumen/bkm_angsuran/{{ $idt }}"
                class="btn btn-instagram btn-tooltip btn-sm btn-link mb-2" data-bs-toggle="tooltip"
                data-bs-placement="top" title="BKM" data-container="body" data-animation="true">
                <span class="btn-inner--icon">
                    <i class="fas fa-file-circle-exclamation"></i> BKM
                </span>
            </button>
        </div>
        <div class="col-md-3 d-grid">
            <button type="button"
                data-action="/perguliran/dokumen/kartu_angsuran/{{ $id_pinkel }}/{{ $idtp }}"
                class="btn btn-tumblr btn-tooltip btn-sm btn-link mb-2" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Cetak Pada Kartu Angsuran" data-container="body" data-animation="true">
                <span class="btn-inner--icon">
                    <i class="fas fa-file-invoice"></i> Cetak Pada Kartu
                </span>
            </button>
        </div>
    </div>
</div>
