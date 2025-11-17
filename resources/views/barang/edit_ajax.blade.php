<div class="modal-dialog modal-lg" role="document">
    <form id="form-update-barang" action="{{ url('/barang/update_ajax/' . $barang->barang_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="barang_nama">Nama Barang</label>
                    <input type="text" class="form-control" id="barang_nama" name="barang_nama"
                        value="{{ $barang->barang_nama ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="" disabled>Pilih kategori</option>
                        @foreach ($kategori_list as $k)
                            <option value="{{ $k->kategori_id }}"
                                {{ $barang->kategori_id == $k->kategori_id ? 'selected' : '' }}>{{ $k->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi</label>
                    <input type="text" class="form-control" id="spesifikasi" name="spesifikasi"
                        value="{{ $barang->spesifikasi ?? '' }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
</div>
