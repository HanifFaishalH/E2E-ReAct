<div class="modal-dialog modal-lg" role="document">
    <form id="form-update-lantai" action="{{ url('/lantai/update_ajax/' . $lantai->lantai_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Lantai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="lantai_nama">Nama Lantai</label>
                    <input type="text" class="form-control" id="lantai_nama" name="lantai_nama"
                        value="{{ $lantai->lantai_nama }}" required>
                </div>

                <div class="form-group">
                    <label for="gedung_id">Gedung</label>
                    <select name="gedung_id" id="gedung_id" class="form-control" required>
                        <option value="" disabled>Pilih gedung</option>
                        @foreach ($gedung as $g)
                            <option value="{{ $g->gedung_id }}"
                                {{ $lantai->gedung_id == $g->gedung_id ? 'selected' : '' }}>
                                {{ $g->gedung_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
</div>