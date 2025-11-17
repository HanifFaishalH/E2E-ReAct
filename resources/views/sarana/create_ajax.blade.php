<div class="modal-dialog modal-lg" role="document">
    <form id="form-create-sarana" action="{{ url('/sarana/store_ajax') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tambah Sarana</h5>
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
                    <label for="ruang_id">Ruang</label>
                    <select name="ruang_id" id="ruang_id" class="form-control" disabled required>
                        <option value="">- Pilih Ruang -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach ($kategori_list ?? [] as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="barang_id">Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" disabled required>
                        <option value="">- Pilih Barang -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="frekuensi_penggunaan">Frekuensi Penggunaan</label>
                    <input type="text" class="form-control" id="frekuensi_penggunaan" name="frekuensi_penggunaan"
                        placeholder="Masukkan frekuensi penggunaan" required>
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
            var ruangSelect = $('#ruang_id');
            var kategoriSelect = $('#kategori_id');
            var barangSelect = $('#barang_id');

            lantaiSelect.empty().append('<option value="" disabled selected>Pilih Lantai</option>')
                .prop('disabled', true);
            ruangSelect.empty().append('<option value="">- Pilih Ruang -</option>').prop('disabled',
                true);
            barangSelect.empty().append('<option value="">- Pilih Barang -</option>').prop('disabled',
                true);

            if (gedungID) {
                $.ajax({
                    url: "{{ url('sarana/ajax/lantai-by-gedung') }}/" + gedungID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log('Data lantai diterima:', data);
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
                        console.error('Ajax error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal mengambil data lantai.', 'error');
                    }
                });
            }
        });

        // Ambil ruang berdasarkan lantai dengan peringatan jika tidak ada ruang
        $('#lantai_id').on('change', function() {
            var lantaiID = $(this).val();
            var ruangSelect = $('#ruang_id');
            var kategoriSelect = $('#kategori_id');
            var barangSelect = $('#barang_id');

            ruangSelect.empty().append('<option value="">- Pilih Ruang -</option>').prop('disabled',
                true);
            barangSelect.empty().append('<option value="">- Pilih Barang -</option>').prop('disabled',
                true);

            if (lantaiID) {
                $.ajax({
                    url: "{{ url('sarana/ajax/ruang-by-lantai') }}/" + lantaiID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log('Data ruang diterima:', data);
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                ruangSelect.append('<option value="' + value
                                    .ruang_id + '">' + value.ruang_nama +
                                    '</option>');
                            });
                            ruangSelect.prop('disabled', false);
                        } else {
                            Swal.fire('Peringatan',
                                'Tidak ada ruang tersedia untuk lantai ini. Lanjutkan jika perlu.',
                                'warning');
                            ruangSelect.prop('disabled',
                            false); // Izinkan memilih meskipun kosong
                        }
                    },
                    error: function(xhr) {
                        console.error('Ajax error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal mengambil data ruang.', 'error');
                    }
                });
            }
        });

        // Ambil barang berdasarkan kategori
        $('#kategori_id').on('change', function() {
            var kategoriID = $(this).val();
            var barangSelect = $('#barang_id');

            barangSelect.empty().append('<option value="">- Pilih Barang -</option>').prop('disabled',
                true);

            if (kategoriID) {
                $.ajax({
                    url: "{{ url('sarana/ajax/barang-by-kategori') }}/" + kategoriID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log('Data barang diterima:', data);
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                barangSelect.append('<option value="' + value
                                    .barang_id + '">' + value.barang_nama +
                                    '</option>');
                            });
                            barangSelect.prop('disabled', false);
                        } else {
                            Swal.fire('Info',
                                'Tidak ada barang tersedia untuk kategori ini.', 'info');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengambil data barang.', 'error');
                    }
                });
            }
        });
    });
</script>
