<div class="modal-dialog modal-lg" role="document">
    <form id="form-create-user" action="{{ url('/user/store_ajax') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="level_id">Level</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Level</option>
                        @foreach($level as $l)
                            <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Masukkan password" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                           placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="no_induk">No Induk (opsional)</label>
                    <input type="text" class="form-control" id="no_induk" name="no_induk"
                           placeholder="Masukkan no induk">
                </div>
                <div class="form-group">
                    <label for="foto">Foto (opsional)</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>