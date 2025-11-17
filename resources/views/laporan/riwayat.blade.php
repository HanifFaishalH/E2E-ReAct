@extends('layout.template')

@section('content')
<div class="container">
    <h1>Riwayat Perbaikan</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Laporan ID</th>
                    <th>Teknisi</th>
                    <th>Tindakan</th>
                    <th>Bahan</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $item)
                    <tr>
                        <td>{{ $item->riwayat_id }}</td>
                        <td>{{ $item->laporan_id }}</td>
                        <td>{{ $item->teknisi->user->username ?? 'N/A' }}</td>
                        <td>{{ $item->tindakan }}</td>
                        <td>{{ $item->bahan }}</td>
                        <td>{{ $item->biaya }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>{{ $item->waktu_mulai instanceof \Carbon\Carbon ? $item->waktu_mulai->format('d-m-Y H:i') : ($item->waktu_mulai ?? '-') }}
                        </td>
                        <td>{{ $item->waktu_selesai instanceof \Carbon\Carbon ? $item->waktu_selesai->format('d-m-Y H:i') : ($item->waktu_selesai ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection