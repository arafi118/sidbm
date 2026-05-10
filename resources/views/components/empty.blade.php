<div class="text-center py-5">
    <div class="mb-3">
        <img src="/assets/img/404.png" alt="No Data" style="max-width: 200px; opacity: 0.8;">
    </div>
    <h4 class="text-secondary font-weight-normal">Data tidak ditemukan</h4>
    <p class="text-muted">Sepertinya belum ada data yang tersedia untuk ditampilkan saat ini.</p>
    @if(isset($buttonLink))
        <a href="{{ $buttonLink }}" class="btn btn-sm bg-gradient-primary mt-2">
            {{ $buttonText ?? 'Refresh Halaman' }}
        </a>
    @endif
</div>
