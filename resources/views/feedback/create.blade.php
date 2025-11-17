@extends('layout.template')

@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Berikan Umpan Balik</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('feedback.store') }}" method="POST" onsubmit="return validateLaporan()">
                            @csrf

                            <div class="form-group">
                                <label for="laporan_id">Pilih Laporan</label>
                                @if ($laporans->isEmpty())
                                    <p>Tidak ada laporan yang tersedia untuk diberi umpan balik.</p>
                                @else
                                    <input type="hidden" name="laporan_id" id="laporan_id" required>

                                    <table class="table table-bordered" id="tabel-laporan">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>Sarana</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($laporans as $index => $laporan)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $laporan->laporan_judul }}</td>
                                                    <td>{{ $laporan->nama_sarana ?? '-' }}</td>
                                                    <td>{{ $laporan->status_laporan }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info pilih-laporan"
                                                            data-id="{{ $laporan->laporan_id }}"
                                                            data-judul="{{ $laporan->laporan_judul }}">
                                                            Pilih
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <p id="laporanTerpilih" class="mt-2 text-success"></p>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="rating">Rating (1-5)</label><br>
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}"
                                            value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="rating{{ $i }}">{{ $i }}</label>
                                    </div>
                                @endfor
                                @error('rating')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="komentar">Komentar</label>
                                <textarea name="komentar" class="form-control" required>{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script langsung ditempel di bawah agar pasti jalan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tombolPilih = document.querySelectorAll('.pilih-laporan');
            const inputLaporanId = document.getElementById('laporan_id');
            const teksLaporanTerpilih = document.getElementById('laporanTerpilih');

            tombolPilih.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const judul = this.dataset.judul;

                    inputLaporanId.value = id;
                    teksLaporanTerpilih.textContent = "Laporan terpilih: " + judul;
                });
            });
        });

        function validateLaporan() {
            const laporanId = document.getElementById('laporan_id').value;
            if (!laporanId) {
                alert("Silakan pilih laporan terlebih dahulu.");
                return false;
            }
            return true;
        }
    </script>
@endsection