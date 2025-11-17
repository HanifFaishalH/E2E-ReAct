<div class="modal-header bg-info text-white">
    <h5 class="modal-title">Detail Gedung {{ $gedung->gedung_kode ?? 'N/A' }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form id="formDetailGedung">
        @csrf
        <input type="hidden" name="gedung_id" value="{{ $gedung->gedung_id }}">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Gedung</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ $gedung->gedung_nama ?? 'N/A' }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kode Gedung</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ $gedung->gedung_kode ?? 'N/A' }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal Dibuat</label>
            <div class="col-sm-9">
                <input type="text" class="form-control"
                    value="{{ $gedung->created_at ? $gedung->created_at->format('d-m-Y H:i') : 'N/A' }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal Diperbarui</label>
            <div class="col-sm-9">
                <input type="text" class="form-control"
                    value="{{ $gedung->updated_at ? $gedung->updated_at->format('d-m-Y H:i') : 'N/A' }}" readonly>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
