<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link id="pagestyle" href="/assets/css/material-dashboard.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">

@php
    $pros = (($per_page * $cur_page) / $total_row) * 100;
@endphp

<body class="min-vh-100 d-flex align-items-center justify-content-center flex-column">
    <div class="mb-2">
        <b>{{ number_format($pros, 2) }}%</b> dari <b>{{ number_format($total_row, 0) }}</b>
        data transaksi berhasil dimigrasi
    </div>
    <a href="{{ $link }}" id="next" class="btn btn-github btn-lg">
        <i class="fas fa-angle-double-right"></i>
        Lanjutkan
        <i class="fas fa-angle-double-left"></i>
    </a>
</body>

<script>
    setTimeout(() => {
        document.querySelector('#next').click();
    }, 1000);
</script>
