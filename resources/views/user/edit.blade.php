<div class="modal-dialog modal-lg" role="document">
    <form id="form-update-user" action="{{ url('/user/update/' . $user->user_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">Nama User</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                           value="{{ $user->nama ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="no_induk">No Induk</label>
                    <input type="text" class="form-control" id="no_induk" name="no_induk"
                           value="{{ $user->no_induk ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                           value="{{ $user->username ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control" id="password" name="password"
                           value="{{ old('password') }}">
                </div>
                <div class="form-group">
                    <label for="level_id">Level</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="" disabled>Pilih Level</option>
                        @foreach ($level as $l)
                            <option value="{{ $l->level_id }}"
                                    {{ $user->level_id == $l->level_id ? 'selected' : '' }}>{{ $l->level_nama }}</option>
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