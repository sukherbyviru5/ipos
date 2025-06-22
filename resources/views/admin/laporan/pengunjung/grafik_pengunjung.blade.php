@extends('layout.kop')

@section('title')
    GRAFIK LAPORAN BULANAN PENGUNJUNG PERPUSTAKAAN<br>
    TAHUN {{ $year }}
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 20px;">
        <h4>GRAFIK LAPORAN BULANAN PENGUNJUNG PERPUSTAKAAN<br>TAHUN {{ $year }}</h4>
    </div>

    <canvas id="grafikPengunjung" height="150"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikPengunjung').getContext('2d');

        const labels = {!! json_encode($labels) !!};

        const datasets = [
            @foreach($dataKelas as $namaKelas => $data)
                {
                    label: "{{ $namaKelas }}",
                    data: {!! json_encode($data) !!},
                    backgroundColor: '{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}'
                },
            @endforeach
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        window.onload = function () {
            setTimeout(() => window.print(), 500);
        };
    </script>
@endsection
