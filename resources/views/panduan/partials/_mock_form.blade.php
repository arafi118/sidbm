<div class="card h-100 border shadow-none">
    <div class="card-body p-3">
        <h6 class="mb-3 text-sm font-weight-bold text-{{ $color ?? 'primary' }}">{{ $sub ?? 'Form Entry' }}</h6>
        
        <div class="input-group input-group-static mb-3">
            <label>Jenis Transaksi</label>
            <input type="text" class="form-control" value="{{ $jt }}" readonly>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Sumber Dana</label>
                    <textarea class="form-control" rows="1" readonly style="resize: none; overflow: hidden;">{{ $sumber }}</textarea>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Disimpan Ke</label>
                    <textarea class="form-control" rows="1" readonly style="resize: none; overflow: hidden;">{{ $simpan }}</textarea>
                </div>
            </div>
        </div>

        {{-- Logic for Relasi & Keterangan (Non-specialized forms) --}}
        @if(!isset($ati) && !isset($hapus_ati))
            @if(isset($relasi))
            <div class="input-group input-group-static mb-3">
                <label>Relasi</label>
                <input type="text" class="form-control" value="{{ is_string($relasi) ? $relasi : 'Pihak Terkait' }}" readonly>
            </div>
            @endif

            <div class="input-group input-group-static mb-3">
                <label>Keterangan</label>
                <textarea class="form-control" rows="1" readonly style="resize: none; overflow: hidden;">{{ $ket }}</textarea>
            </div>
        @endif

        {{-- Specialized Form for ATI Purchase --}}
        @if(isset($ati))
        <div class="input-group input-group-static mb-3">
            <label>Relasi</label>
            <input type="text" class="form-control" value="Toko Abadi Jaya" readonly>
        </div>
        @endif

        @if(isset($ati))
        <div class="row">
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" value="Laptop Kantor" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Jml. Unit</label>
                    <input type="text" class="form-control" value="1" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Harga Satuan</label>
                    <input type="text" class="form-control" value="7,500,000" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Umur Eko. (bln)</label>
                    <input type="text" class="form-control" value="48" readonly>
                </div>
            </div>
        </div>
        @endif

        @if(isset($hapus_ati))
        <div class="row">
            <div class="col-8">
                <div class="input-group input-group-static mb-3">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" value="001. Meja Kerja (5 unit)" readonly>
                </div>
            </div>
            <div class="col-4">
                <div class="input-group input-group-static mb-3">
                    <label>Alasan</label>
                    <input type="text" class="form-control" value="Rusak" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Jumlah (unit)</label>
                    <input type="text" class="form-control" value="2" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-static mb-3">
                    <label>Nilai Buku</label>
                    <input type="text" class="form-control" value="500,000" readonly>
                </div>
            </div>
        </div>
        @endif

        @if(isset($note))
            <div class="mt-3">
                <p class="text-xs text-info mb-0 d-flex align-items-baseline">
                    <i class="material-icons text-xs me-1">info</i>
                    <span>{{ $note }}</span>
                </p>
            </div>
        @endif
    </div>
</div>
