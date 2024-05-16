@php
    use App\Utils\Tanggal;
@endphp

@extends('kabupaten.layout.base')

@section('content')
    @foreach ($saldo_kec as $sk)
        <input type="hidden" data-input="chart-saldo" name="saldo[]" id="{{ $sk['kode'] }}"
            value="{{ $sk['nama'] . '#' . $sk['surplus'] }}">
    @endforeach

    <div class="card mb-4 mt-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                <div class="chart">
                    <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h6 class="mb-0 ">Laba rugi per kecamatan</h6>
            <p class="text-sm ">s.d. Tanggal {{ Tanggal::tglLatin(date('Y-m-d')) }}</p>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const saldo = document.querySelectorAll('input[data-input="chart-saldo"]');
        let labels = [];
        let data_saldo = [];

        saldo.forEach(input => {
            const value = input.getAttribute('value');
            const label = value.split('#')[0];
            const data = value.split('#')[1];

            labels.push(label);
            data_saldo.push(data);
        });

        var ctx = document.getElementById("chart-bars").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Surplus",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "rgba(255, 255, 255, .8)",
                    data: data_saldo,
                    maxBarThickness: 6
                }, ],
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
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
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
    </script>
@endsection
