<div class="modal-dialog" role="document">
    <form id="form-delete-user" action="{{ url('/user/destroy_ajax/' . $user->user_id) }}" method="POST">
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
                <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->username }}</strong> ({{ $user->nama }})?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
</div>