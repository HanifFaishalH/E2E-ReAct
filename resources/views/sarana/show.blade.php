<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Sarana {{ $sarana->sarana_kode ?? 'N/A' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-white">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formDetailSarana">
                @csrf
                <input type="hidden" name="sarana_id" value="{{ $sarana->sarana_id ?? '' }}">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kode Sarana</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->sarana_kode ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Gedung</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->gedung->gedung_nama ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Ruang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->ruang->ruang_nama ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Lantai</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $sarana->ruang->lantai->lantai_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kategori</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $sarana->kategori->kategori_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->barang->barang_nama ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jumlah Laporan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->jumlah_laporan ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nomor Urut</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->nomor_urut ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Frekuensi Penggunaan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $sarana->frekuensi_penggunaan ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Operasional</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $sarana->tanggal_operasional ? date('d-m-Y', strtotime($sarana->tanggal_operasional)) : 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Dibuat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $sarana->created_at ? $sarana->created_at->format('d-m-Y H:i') : 'N/A' }}"
                            readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Diperbarui</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                            value="{{ $sarana->updated_at ? $sarana->updated_at->format('d-m-Y H:i') : 'N/A' }}"
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
