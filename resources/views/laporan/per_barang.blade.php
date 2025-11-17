@extends('layout.template')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Barang yang Sering Dilaporkan Rusak</h2>

    <canvas id="chartBarang" height="100" class="mb-5"></canvas>

    <div class="table-responsive">
        <table id="tabelBarang" class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah Laporan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $item)
                    <tr>
                        <td>{{ $item->barang_nama }}</td>
                        <td>{{ $item->total_laporan }}</td>
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
        $('#tabelBarang').DataTable();

        const ctx = document.getElementById('chartBarang').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($barang->pluck('barang_nama')) !!},
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: {!! json_encode($barang->pluck('total_laporan')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
                        title: { display: true, text: 'Nama Barang' }
                    }
                }
            }
        });
    });
</script>
@endpush
