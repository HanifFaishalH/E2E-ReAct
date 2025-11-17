<div class="modal-dialog modal-lg" role="document">
    <form id="form-create-ruang" action="{{ url('/ruang/store_ajax') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tambah Ruang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="gedung_id">Gedung</label>
                    <select name="gedung_id" id="gedung_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Gedung</option>
                        @foreach ($gedung_list ?? [] as $g)
                            <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="lantai_id">Lantai</label>
                    <select name="lantai_id" id="lantai_id" class="form-control" disabled required>
                        <option value="" disabled selected>Pilih Lantai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ruang_kode">Kode Ruang</label>
                    <input type="text" class="form-control" id="ruang_kode" name="ruang_kode"
                        placeholder="Masukkan kode ruang" required>
                </div>
                <div class="form-group">
                    <label for="ruang_nama">Nama Ruang</label>
                    <input type="text" class="form-control" id="ruang_nama" name="ruang_nama"
                        placeholder="Masukkan nama ruang" required>
                </div>
                <div class="form-group">
                    <label for="ruang_tipe">Tipe Ruang</label>
                    <select name="ruang_tipe" id="ruang_tipe" class="form-control" required>
                        <option value="" disabled selected>Pilih tipe ruang</option>
                        <option value="Lab">Lab</option>
                        <option value="Kelas">Kelas</option>
                        <option value="Kantor">Kantor</option>
                        <option value="Toilet">Toilet</option>
                        <option value="Mushola">Mushola</option>
                    </select>
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
    $(document).ready(function() {
        // Ambil lantai berdasarkan gedung
        $('#gedung_id').on('change', function() {
            var gedungID = $(this).val();
            var lantaiSelect = $('#lantai_id');

            lantaiSelect.empty().append('<option value="" disabled selected>Pilih Lantai</option>')
                .prop('disabled', true);

            if (gedungID) {
                $.ajax({
                    url: "{{ url('ruang/ajax/lantai-by-gedung') }}/" + gedungID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log('Data lantai diterima:', data); // Debugging
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                lantaiSelect.append('<option value="' + value
                                    .lantai_id + '">' + value.lantai_nama +
                                    '</option>');
                            });
                            lantaiSelect.prop('disabled', false);
                        } else {
                            Swal.fire('Info', 'Tidak ada lantai tersedia untuk gedung ini.',
                                'info');
                        }
                    },
                    error: function(xhr) {
                        console.error('Ajax error:', xhr.responseText); // Debugging
                        Swal.fire('Error', 'Gagal mengambil data lantai.', 'error');
                    }
                });
            }
        });
    });
</script>
