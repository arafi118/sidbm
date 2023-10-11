@extends('layouts.base')

@section('content')
    <form action="" method="post" id="defaultForm">
        @csrf

        <input type="hidden" name="tgl" id="tgl" value="{{ date('d/m/Y') }}">
    </form>

    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-7 text-start">
                            <p class="text-sm mb-1 text-capitalize font-weight-bold">Pemanfaat</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $pinjaman_kelompok }} Kelompok
                            </h5>
                            <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">
                                {{ $pinjaman_anggota }}
                                <span class="font-weight-normal text-secondary">pemanfaat Aktif</span>
                            </span>
                        </div>
                        <div class="col-5">
                            <div class="dropdown text-end">
                                <span class="text-xs text-secondary">Periode {{ date('d/m/y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-7 text-start">
                            <p class="text-sm mb-1 text-capitalize font-weight-bold">Proposal Pinjaman</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $proposal }} Proposal
                            </h5>
                            <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">
                                {{ $verifikasi }}
                                <span class="font-weight-normal text-secondary">verifikasi</span>
                            </span>
                        </div>
                        <div class="col-5">
                            <div class="dropdown text-end">
                                <span class="text-xs text-secondary">{{ $waiting }} waiting</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative pointer" id="btnjatuhTempo">
                    <div class="row">
                        <div class="col-7 text-start">
                            <p class="text-sm mb-1 text-capitalize font-weight-bold">Jatuh Tempo</p>
                            <h5 class="font-weight-bolder mb-0">
                                <span id="jatuh_tempo">
                                    <div class="spinner-border sm text-info" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span> Hari Ini
                            </h5>
                            <span class="font-weight-normal text-secondary text-sm">
                                <span class="font-weight-bolder text-success" id="nunggak">
                                    <div class="spinner-border xs text-info" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span> menunggak
                            </span>
                        </div>
                        <div class="col-5">
                            <div class="dropdown text-end">
                                <span class="text-xs text-warning">&#33; tagihan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">Angsuran Hari Ini</h6>
                        <button type="button"
                            class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                            data-bs-original-title="See traffic channels">
                            <i class="material-icons text-sm">priority_high</i>
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0 p-3 pt-0 mt-4">
                    <div class="row">
                        <div class="col-7 text-start">
                            <div class="chart">
                                <canvas id="chart-pie" class="chart-canvas" height="400"
                                    style="display: block; box-sizing: border-box; height: 200px; width: 169.7px;"
                                    width="339"></canvas>
                            </div>
                        </div>
                        <div class="col-5 my-auto">
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-info"></i>
                                <span class="text-dark text-xs">SPP Pokok</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs">SPP Jasa</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-dark"></i>
                                <span class="text-dark text-xs">UEP Pokok</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-secondary"></i>
                                <span class="text-dark text-xs">UEP Jasa</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-danger"></i>
                                <span class="text-dark text-xs">PL Pokok</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 d-block text-start">
                                <i class="bg-warning"></i>
                                <span class="text-dark text-xs">PL Jasa</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 pb-2 p-3 d-flex align-items-center">
                    <div class="w-60">
                        <div class="text-sm">
                            Total Angsuran
                            <div>
                                <b>Rp. <span id="total_angsur"></span></b>
                            </div>
                        </div>
                    </div>
                    <div class="w-40 text-end">
                        <a class="btn bg-light mb-0 text-end" href="javascript:;">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-6 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">Pendapatan dan Beban</h6>
                        <button type="button"
                            class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                            data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-original-title="See which ads perform better">
                            <i class="material-icons text-sm">priority_high</i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-success"></i>
                            <span class="text-dark text-xs">Pendapatan</span>
                        </span>
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-warning"></i>
                            <span class="text-dark text-xs">Beban</span>
                        </span>
                        <span class="badge badge-md badge-dot me-4">
                            <i class="bg-info"></i>
                            <span class="text-dark text-xs">Laba</span>
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="400"
                            style="display: block; box-sizing: border-box; height: 210px; width: 844.4px;"
                            width="1688"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cetak Dokumen Pencairan --}}
    <div class="modal fade" id="jatuhTempo" tabindex="-1" aria-labelledby="jatuhTempoLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jatuhTempoLabel">Jatuh Tempo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#hari_ini"
                                    role="tab" aria-controls="hari_ini" aria-selected="true">
                                    Hari Ini
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#menunggak" role="tab"
                                    aria-controls="menunggak" aria-selected="false">
                                    Menunggak
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#tagihan" role="tab"
                                    aria-controls="tagihan" aria-selected="false">
                                    Tagihan
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show active" id="hari_ini" role="tabpanel"
                                aria-labelledby="hari_ini">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped midle" width="100%">
                                                <thead>
                                                    <tr>
                                                        <td align="center">No</td>
                                                        <td align="center">Nama Kelompok</td>
                                                        <td align="center">Tgl Cair</td>
                                                        <td align="center">Alokasi</td>
                                                        <td align="center">Tunggakan Pokok</td>
                                                        <td align="center">Tunggakan Jasa</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="TbHariIni"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="menunggak" role="tabpanel" aria-labelledby="menunggak">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped midle" width="100%">
                                                <thead>
                                                    <tr>
                                                        <td align="center">No</td>
                                                        <td align="center">Nama Kelompok</td>
                                                        <td align="center">Tgl Cair</td>
                                                        <td align="center">Alokasi</td>
                                                        <td align="center">Tunggakan Pokok</td>
                                                        <td align="center">Tunggakan Jasa</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="TbMenunggak"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $p = $saldo[4];
        $b = $saldo[5];
        $surplus = $saldo['surplus'];
    @endphp
@endsection

@section('script')
    <script>
        $.ajax({
            type: 'post',
            url: '/dashboard/jatuh_tempo',
            data: $('#defaultForm').serialize(),
            success: function(result) {
                if (result.success) {
                    $('#jatuh_tempo').html(result.jatuh_tempo)

                    if (result.jatuh_tempo != '00') {
                        $('#TbHariIni').html(result.hari_ini)
                    }
                }
            }
        })

        $.ajax({
            type: 'post',
            url: '/dashboard/nunggak',
            data: $('#defaultForm').serialize(),
            success: function(result) {
                if (result.success) {
                    $('#nunggak').html(result.nunggak)

                    if (result.nunggak != '00') {
                        $('#TbMenunggak').html(result.table)
                    }
                }
            }
        })
    </script>

    <script>
        var formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })

        var ctx1 = document.getElementById("chart-line").getContext("2d");
        var ctx2 = document.getElementById("chart-pie").getContext("2d");

        // Line chart
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "Mei",
                    "Jun",
                    "Jul",
                    "Agu",
                    "Sep",
                    "Okt",
                    "Nov",
                    "Des",
                ],
                datasets: [{
                        label: "Pendapatan",
                        tension: 0,
                        pointRadius: 5,
                        pointBackgroundColor: "#4CAF50",
                        pointBorderColor: "transparent",
                        borderColor: "#4CAF50",
                        borderWidth: 2,
                        backgroundColor: "transparent",
                        fill: true,
                        data: [
                            "{{ $p['1'] }}",
                            "{{ $p['2'] }}",
                            "{{ $p['3'] }}",
                            "{{ $p['4'] }}",
                            "{{ $p['5'] }}",
                            "{{ $p['6'] }}",
                            "{{ $p['7'] }}",
                            "{{ $p['8'] }}",
                            "{{ $p['9'] }}",
                            "{{ $p['10'] }}",
                            "{{ $p['11'] }}",
                            "{{ $p['12'] }}"
                        ],
                        maxBarThickness: 6
                    },
                    {
                        label: "Beban",
                        tension: 0,
                        borderWidth: 0,
                        pointRadius: 5,
                        pointBackgroundColor: "#fb8c00",
                        pointBorderColor: "transparent",
                        borderColor: "#fb8c00",
                        borderWidth: 2,
                        backgroundColor: "transparent",
                        fill: true,
                        data: [
                            "{{ $b['1'] }}",
                            "{{ $b['2'] }}",
                            "{{ $b['3'] }}",
                            "{{ $b['4'] }}",
                            "{{ $b['5'] }}",
                            "{{ $b['6'] }}",
                            "{{ $b['7'] }}",
                            "{{ $b['8'] }}",
                            "{{ $b['9'] }}",
                            "{{ $b['10'] }}",
                            "{{ $b['11'] }}",
                            "{{ $b['12'] }}"
                        ],
                        maxBarThickness: 6
                    },
                    {
                        label: "Laba",
                        tension: 0,
                        borderWidth: 0,
                        pointRadius: 5,
                        pointBackgroundColor: "#1A73E8",
                        pointBorderColor: "transparent",
                        borderColor: "#1A73E8",
                        borderWidth: 2,
                        backgroundColor: "transparent",
                        fill: true,
                        data: [
                            "{{ $surplus['1'] }}",
                            "{{ $surplus['2'] }}",
                            "{{ $surplus['3'] }}",
                            "{{ $surplus['4'] }}",
                            "{{ $surplus['5'] }}",
                            "{{ $surplus['6'] }}",
                            "{{ $surplus['7'] }}",
                            "{{ $surplus['8'] }}",
                            "{{ $surplus['9'] }}",
                            "{{ $surplus['10'] }}",
                            "{{ $surplus['11'] }}",
                            "{{ $surplus['12'] }}"
                        ],
                        maxBarThickness: 6
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: '#c1c4ce5c'
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#9ca2b7',
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                            color: '#c1c4ce5c'
                        },
                        ticks: {
                            display: true,
                            color: '#9ca2b7',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });

        // Pie chart
        new Chart(ctx2, {
            type: "pie",
            data: {
                labels: [
                    'SPP Pokok',
                    'SPP Jasa',
                    'UEP Pokok',
                    'UEP Jasa',
                    'PL Pokok',
                    'PL Jasa'
                ],
                datasets: [{
                    label: "Projects",
                    weight: 9,
                    cutout: 0,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 1,
                    backgroundColor: [
                        '#1a73e8',
                        '#4caf50',
                        '#344767',
                        '#7b809a',
                        '#f44335',
                        '#fb8c00',
                    ],
                    data: [
                        "{{ $pokok_spp }}",
                        "{{ $jasa_spp }}",
                        "{{ $pokok_uep }}",
                        "{{ $jasa_uep }}",
                        "{{ $pokok_pl }}",
                        "{{ $jasa_pl }}",
                    ],
                    fill: false
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            color: '#c1c4ce5c'
                        },
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            color: '#c1c4ce5c'
                        },
                        ticks: {
                            display: false,
                        }
                    },
                },
            },
        });

        var total_angsur =
            "{{ $pokok_spp + $jasa_spp + $pokok_uep + $jasa_uep + $pokok_pl + $jasa_pl }}"

        $('#total_angsur').html(formatter.format(total_angsur))

        $(document).on('click', '#btnjatuhTempo', function(e) {
            e.preventDefault()

            $('#jatuhTempo').modal('show')
        })
    </script>
@endsection
