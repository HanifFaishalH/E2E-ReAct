<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Periode</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        h3 { text-align: center; margin: 0; }
    </style>
</head>
<body>
    <h3>Laporan Kerusakan per Periode</h3>

    <p><strong>Filter:</strong><br>
        Tahun: {{ $tahun ?? 'Semua' }}<br>
        Bulan: {{ $bulan ? \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') : 'Semua' }}<br>
        Barang: {{ $barangInfo ? $barangInfo->barang_nama . ' (Kategori: ' . $barangInfo->kategori_nama . ')' : 'Semua' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Jumlah Laporan</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['tahun'] }}</td>
                <td>{{ $item['bulan'] }}</td>
                <td>{{ $item['jumlah'] }}</td>
                <td>{{ number_format(($item['jumlah'] / $total) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
