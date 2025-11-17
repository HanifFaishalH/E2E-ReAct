<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Laporan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="gedung"><strong>Gedung</strong></label>
                <input type="text" class="form-control" id="gedung" value="{{ optional($laporan->gedung)->gedung_nama ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label for="lantai"><strong>Lantai</strong></label>
                <input type="text" class="form-control" id="lantai" value="{{ optional($laporan->lantai)->lantai_nama ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label for="ruang"><strong>Ruang</strong></label>
                <input type="text" class="form-control" id="ruang" value="{{ optional($laporan->ruang)->ruang_nama ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label for="sarana"><strong>Sarana</strong></label>
                <input type="text" class="form-control" id="sarana" value="{{ optional($laporan->sarana)->sarana_kode ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label for="teknisi"><strong>Teknisi</strong></label>
                <input type="text" class="form-control" id="teknisi" value="{{ optional(optional($laporan->teknisi)->user)->name ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label for="laporan_judul"><strong>Judul Laporan</strong></label>
                <input type="text" class="form-control" id="laporan_judul" value="{{ $laporan->laporan_judul ?? '-' }}" readonly>
            </div>
            <div class="form-group">
                <label class="col-sm-3 col-form-label">Foto Laporan</label>
                <div class="col-sm-9">
                    @if($laporan->laporan_foto)
                        <img src="{{ $laporan->laporan_foto }}" class="img-fluid" alt="Foto Laporan" style="max-width:100%; height:auto;">
                    @else
                        <input type="text" class="form-control" value="Tidak ada foto tersedia" readonly>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="tingkat_kerusakan"><strong>Tingkat Kerusakan</strong></label>
                <input type="text" class="form-control" id="tingkat_kerusakan" value="{{ ucfirst($laporan->tingkat_kerusakan ?? '-') }}" readonly>
            </div>
            <div class="form-group">
                <label for="tingkat_urgensi"><strong>Tingkat Urgensi</strong></label>
                <input type="text" class="form-control" id="tingkat_urgensi" value="{{ ucfirst($laporan->tingkat_urgensi ?? '-') }}" readonly>
            </div>
            <div>
                <label for="status_laporan"><strong>Status Laporan</strong></label>
                <input type="text" class="form-control" id="laporan_deskripsi" rows="4" value="{{ ucfirst($laporan->status_laporan ?? '-') }}" readonly></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

