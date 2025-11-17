
    <form id="form-create-gedung" action="{{ url('/gedung/store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tambah Gedung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="gedung_nama">Nama Gedung</label>
                    <input type="text" class="form-control" id="gedung_nama" name="gedung_nama"
                        placeholder="Masukkan nama gedung" required>
                </div>
                <div class="form-group">
                    <label for="gedung_kode">Kode Gedung</label>
                    <input type="text" class="form-control" id="gedung_kode" name="gedung_kode"
                        placeholder="Masukkan kode gedung" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="type" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
