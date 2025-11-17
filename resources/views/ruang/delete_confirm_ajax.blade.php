<div class="modal-dialog" role="document">
    <form id="form-delete-ruang" action="{{ url('/ruang/destroy_ajax/' . $ruang->ruang_id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus ruang <strong>{{ $ruang->ruang_nama }}</strong> ({{ $ruang->ruang_kode }})?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>