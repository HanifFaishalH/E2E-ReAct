<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Kerjakan Laporan</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <form id="formFinishLaporan" method="POST" action="{{ route('laporan.selesai', $laporan->laporan_id) }}">
            @csrf
            <input type="hidden" name="laporan_id" value="{{ $laporan->laporan_id }}">
            <div class="form-group">
                <label for="tindakan">Tindakan</label>
                <textarea class="form-control" id="tindakan" name="tindakan" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="bahan">Bahan</label>
                <textarea class="form-control" id="bahan" name="bahan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="biaya">Biaya</label>
                <input type="number" class="form-control" id="biaya" name="biaya" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>