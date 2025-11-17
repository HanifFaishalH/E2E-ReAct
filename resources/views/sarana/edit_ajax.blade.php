<div class="modal-dialog modal-lg" role="document">
    <form id="form-update-sarana" action="{{ url('/sarana/update_ajax/' . $sarana->sarana_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Sarana</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ruang_id">Ruang</label>
                    <select name="ruang_id" id="ruang_id" class="form-control" required>
                        <option value="" disabled>Pilih ruang</option>
                        @foreach ($ruang_list as $r)
                            <option value="{{ $r->ruang_id }}"
                                {{ $sarana->ruang_id == $r->ruang_id ? 'selected' : '' }}>{{ $r->ruang_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
</div>
