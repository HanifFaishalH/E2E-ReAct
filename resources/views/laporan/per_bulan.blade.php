@extends('layout.template')

@section('content')
<h2 class="mb-4">Laporan Kerusakan per Bulan</h2>

<canvas id="chartPerBulan" height="120"></canvas>

<hr>

<table id="tabelPerBulan" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>Tahun</th>
            <th>Bulan</th>
            <th>Jumlah Laporan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporan as $item)
        <tr>
            <td>{{ $item->tahun }}</td>
            <td>{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</td>
            <td>{{ $item->jumlah_laporan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        $('#tabelPerBulan').DataTable();

        const ctx = document.getElementById('chartPerBulan').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    });
</script>
@endpush
