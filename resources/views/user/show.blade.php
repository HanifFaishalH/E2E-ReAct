<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail User {{ $user->nama ?? 'N/A' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-white">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formDetailUser">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->user_id ?? '' }}">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">ID User</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $user->user_id ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $user->username ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">No Induk</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $user->no_induk ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $user->nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Level</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $user->level->level_nama ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Dibuat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                               value="{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : 'N/A' }}"
                               readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Diperbarui</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"
                               value="{{ $user->updated_at ? $user->updated_at->format('d-m-Y H:i') : 'N/A' }}"
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