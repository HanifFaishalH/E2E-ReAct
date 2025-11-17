<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail Laporan</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        @csrf
        <input type="hidden" name="laporan_id" value="{{ $laporan->laporan_id }}">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Judul Laporan</label>
                    <input type="text" class="form-control" value="{{ $laporan->laporan_judul ?? '-' }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Gedung</label>
                    <input type="text" class="form-control" value="{{ $laporan->gedung->gedung_nama ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Lantai</label>
                    <input type="text" class="form-control" value="{{ $laporan->lantai->lantai_nama ?? '-' }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Ruang</label>
                    <input type="text" class="form-control" value="{{ $laporan->ruang->ruang_nama ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Sarana</label>
                    <input type="text" class="form-control" value="{{ $laporan->sarana->barang->barang_nama ?? '-' }}"
                        readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Jumlah Laporan</label>
                    <input type="text" class="form-control" value="{{ $laporan->sarana->jumlah_laporan ?? 0 }}"
                        readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Teknisi yang Ditugaskan</label>
                    <input type="text" class="form-control"
                        value="{{ $laporan->teknisi->user->username ?? 'Belum ditugaskan' }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Foto Laporan</label>
                    @if ($laporan->laporan_foto)
                        <img src="{{ $laporan->laporan_foto }}" alt="Foto Laporan" class="img-fluid"
                            style="max-width: 100%; height: auto;">
                    @else
                        <p>Tidak ada foto</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tingkat Kerusakan</label>
                    <input type="text" class="form-control" value="{{ $laporan->tingkat_kerusakan ?? '-' }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tingkat Urgensi</label>
                    <input type="text" class="form-control" value="{{ $laporan->tingkat_urgensi ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Frekuensi Penggunaan</label>
                    <input type="text" class="form-control" value="{{ $laporan->sarana->frekuensi_penggunaan ?? '-' }}"
                        readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal Operasional</label>
                    <input type="text" class="form-control" value="{{ $laporan->sarana->tanggal_operasional ?? '-' }}"
                        readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Usia (Sejak Operasional)</label>
                    <div class="form-control">
                        <p>@if ($laporan->sarana->tanggal_operasional)
                            @php
                                $operasional = \Carbon\Carbon::parse($laporan->sarana->tanggal_operasional);
                                $now = \Carbon\Carbon::now();
                                $usia = $operasional->diff($now);
                            @endphp
                            {{ $usia->y }} tahun, {{ $usia->m }} bulan, {{ $usia->d }} hari
                        @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(strtolower($laporan->status_laporan) === 'pending')
            <form id="formDetailLaporan" method="POST" action="{{ route('laporan.accept', $laporan->laporan_id) }}">
                @csrf
                <input type="hidden" name="laporan_id" value="{{ $laporan->laporan_id }}">
                <button type="submit" class="btn btn-primary">Terima Laporan</button>
            </form>
            <form id="formRejectLaporan" method="POST" action="{{ route('laporan.reject', $laporan->laporan_id) }}">
                @csrf
                <input type="hidden" name="laporan_id" value="{{ $laporan->laporan_id }}">
                <button type="submit" class="btn btn-danger">Tolak Laporan</button>
            </form>
        @endif

        @if($user->level_id == 5)
            @if($laporan->status_laporan === 'dikerjakan')
                <button id="finishLaporanButton" data-id="{{ $laporan->laporan_id }}"
                    class="btn btn-success btn-sm">Kerjakan</button>
            @endif
        @endif

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
</div>