<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Ruang {{ $ruang->ruang_kode ?? 'N/A' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-white">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formDetailRuang">
                @csrf
                <input type="hidden" name="ruang_id" value="{{ $ruang->ruang_id ?? '' }}">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Gedung</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $ruang->gedung->gedung_nama ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Lantai</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $ruang->lantai->lantai_nama ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Ruang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $ruang->ruang_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kode Ruang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $ruang->ruang_kode ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tipe Ruang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $ruang->ruang_tipe ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Dibuat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $ruang->created_at ? $ruang->created_at->format('d-m-Y H:i') : 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Diperbarui</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $ruang->updated_at ? $ruang->updated_at->format('d-m-Y H:i') : 'N/A' }}" readonly>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
