<div class="modal-dialog modal-lg" role="document">
    <form id="form-create-barang" action="{{ url('/barang/store_ajax') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="barang_nama">Nama Barang</label>
                    <input type="text" class="form-control" id="barang_nama" name="barang_nama"
                        placeholder="Masukkan nama barang" required>
                </div>
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        @foreach ($kategori_list as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi</label>
                    <input type="text" class="form-control" id="spesifikasi" name="spesifikasi"
                        placeholder="Masukkan spesifikasi" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
</div>
<script>
    $('#form-create-barang').on('submit', function(e) {
        e.preventDefault(); // Hindari submit form biasa
        e.stopImmediatePropagation(); // Mencegah multiple submission

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#form-create-barang')[0].reset(); // Reset form
                    $('#myModal').modal('hide'); // Tutup modal (ganti ID sesuai modal kamu)
                    $('#barang-table').DataTable().ajax.reload(null, false); // Reload tabel DataTable

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Barang berhasil ditambahkan!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Gagal menambahkan barang.'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += `<div>${value[0]}</div>`;
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: errorMsg
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan server.'
                    });
                }
            }
        });

        return false; // Mencegah form submit default
    });
</script>
