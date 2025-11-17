<div class="modal-dialog" role="document">
    <form id="form-delete-lantai" action="{{ url('/lantai/destroy_ajax/' . $lantai->lantai_id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus lantai <strong>{{ $lantai->lantai_nama }}</strong>
                    ({{ $lantai->lantai_nama }})?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>