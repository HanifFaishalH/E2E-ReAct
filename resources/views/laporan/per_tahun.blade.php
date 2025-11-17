@extends('layout.template')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Laporan Kerusakan per Tahun</h2>

        <!-- Chart -->
        <canvas id="chartPerTahun" height="100" class="mb-5"></canvas>

        <!-- Tabel -->
        <div class="table-responsive">
            <table id="tabelPerTahun" class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Tahun</th>
                        <th>Jumlah Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $item)
                        <tr>
                            <td>{{ $item->tahun }}</td>
                            <td>{{ $item->jumlah_laporan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            $('#tabelPerTahun').DataTable();

            const ctx = document.getElementById('chartPerTahun').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($laporan->pluck('tahun')) !!},
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: {!! json_encode($laporan->pluck('jumlah_laporan')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Laporan' }
                        },
                        x: {
                            title: { display: true, text: 'Tahun' }
                        }
                    }
                }
            });
        });
    </script>
@endpush