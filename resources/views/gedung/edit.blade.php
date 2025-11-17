<div class="modal-header bg-warning text-white">
    <h5 class="modal-title">Edit Gedung #{{ $gedung->gedung_kode ?? 'N/A' }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form id="editGedungForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="gedung_id" value="{{ $gedung->gedung_id }}">
        <div class="form-group row">
            <label for="gedung_nama" class="col-sm-3 col-form-label">Nama Gedung</label>
            <div class="col-sm-9">
                <input type="text" name="gedung_nama" class="form-control @error('gedung_nama') is-invalid @enderror"
                    value="{{ old('gedung_nama', $gedung->gedung_nama) }}" required>
                @error('gedung_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="gedung_kode" class="col-sm-3 col-form-label">Kode Gedung</label>
            <div class="col-sm-9">
                <input type="text" name="gedung_kode" class="form-control @error('gedung_kode') is-invalid @enderror"
                    value="{{ old('gedung_kode', $gedung->gedung_kode) }}" required>
                @error('gedung_kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="submit" form="editGedungForm" class="btn btn-primary">Simpan Perubahan</button>
</div>
