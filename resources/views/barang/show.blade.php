<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Barang {{ $barang->barang_nama ?? 'N/A' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-white">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formDetailBarang">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barang->barang_id ?? '' }}">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">ID Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $barang->barang_id ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $barang->barang_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kategori</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $barang->kategori->kategori_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Spesifikasi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $barang->spesifikasi ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Dibuat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $barang->created_at ? $barang->created_at->format('d-m-Y H:i') : 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Diperbarui</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $barang->updated_at ? $barang->updated_at->format('d-m-Y H:i') : 'N/A' }}"
                            readonly>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
