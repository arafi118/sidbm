@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-7 text-start">
                            <p class="text-sm mb-1 text-capitalize font-weight-bold">Pemanfaat</p>
                            <h5 class="font-weight-bolder mb-0">
                                12 Kelompok
                            </h5>
                            <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">55 <span
                                    class="font-weight-normal text-secondary">pemanfaat Aktif</span></span>
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
                                19 Proposal
                            </h5>
                            <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">12 <span
                                    class="font-weight-normal text-secondary">verifikasi</span></span>
                        </div>
                        <div class="col-5">
                            <div class="dropdown text-end">
                                <span class="text-xs text-secondary">2 waiting</span>
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
                            <p class="text-sm mb-1 text-capitalize font-weight-bold">Jatuh Tempo</p>
                            <h5 class="font-weight-bolder mb-0">
                                0 Hari Ini
                            </h5>
                            <span class="font-weight-normal text-secondary text-sm"><span
                                    class="font-weight-bolder text-success">13</span> menunggak</span>
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
                            <div><b>Rp. 123.123.123,00</b></div>
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
@endsection

@section('script')
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");
        var ctx2 = document.getElementById("chart-pie").getContext("2d");

        // Line chart
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
                        data: [50, 100, 200, 190, 400, 350, 500, 450, 700],
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
                        data: [10, 30, 40, 120, 150, 220, 280, 250, 280],
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
                        data: [15, 35, 23, 6, 74, 57, 87, 22, 54],
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
                labels: ['Facebook', 'Direct', 'Organic', 'Referral'],
                datasets: [{
                    label: "Projects",
                    weight: 9,
                    cutout: 0,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 1,
                    backgroundColor: ['#17c1e8', '#e91e63', '#3A416F', '#a8b8d8'],
                    data: [15, 20, 12, 60],
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
    </script>
@endsection
