<div class="modal-dialog modal-lg" role="document">
    <form id="form-create-ruang" action="{{ url('/lantai/store_ajax') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">{{ isset($data) ? 'Edit' : 'Tambah' }} Lantai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="gedung_id">Gedung</label>
                    <select name="gedung_id" id="gedung_id" class="form-control" required>
                        <option value="" disabled selected>Pilih gedung</option>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->gedung_id }}"
                                {{ isset($data) && $data->gedung_id == $g->gedung_id ? 'selected' : '' }}>
                                {{ $g->gedung_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="lantai_nama">Nama Lantai</label>
                    <input type="text" class="form-control" id="lantai_nama" name="lantai_nama"
                        value="{{ $data->lantai_nama ?? '' }}" placeholder="Masukkan nama lantai" required>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>
