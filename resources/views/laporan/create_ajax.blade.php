<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ url('/laporan/store_ajax') }}" method="POST" id="form-create-laporan" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Laporan Kerusakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Gedung --}}
                <div class="form-group">
                    <label>Gedung</label>
                    @if ($gedung)
                        <input type="hidden" name="gedung_id" value="{{ $gedung->gedung_id }}">
                        <input type="text" class="form-control" value="{{ $gedung->gedung_nama }}" readonly>
                    @else
                        <input type="hidden" name="gedung_id" value="">
                        <input type="text" class="form-control" value="Tidak ada gedung tersedia" readonly>
                    @endif
                    <small id="error-gedung_id" class="error-text form-text text-danger"></small>
                </div>

                {{-- Lantai --}}
                <div class="form-group">
                    <label>Lantai</label>
                    <select name="lantai_id" id="lantai_id" class="form-control" required>
                        <option value="">- Pilih Lantai -</option>
                        @foreach ($lantai as $l)
                            <option value="{{ $l->lantai_id }}">{{ $l->lantai_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-lantai_id" class="error-text form-text text-danger"></small>
                </div>

                {{-- Ruang --}}
                <div class="form-group">
                    <label>Ruang</label>
                    <select name="ruang_id" id="ruang_id" class="form-control" disabled required>
                        <option value="">- Pilih Ruang -</option>
                    </select>
                    <small id="error-ruang_id" class="error-text form-text text-danger"></small>
                </div>

                {{-- Sarana --}}
                <div class="form-group">
                    <label>Sarana</label>
                    <select name="sarana_id" id="sarana_id" class="form-control" disabled required>
                        <option value="">- Pilih Sarana -</option>
                    </select>
                    <small id="error-sarana_id" class="error-text form-text text-danger"></small>
                </div>

                {{-- Judul Laporan --}}
                <div class="form-group">
                    <label>Judul Laporan</label>
                    <input type="text" name="laporan_judul" class="form-control" maxlength="100" required>
                    <small id="error-laporan_judul" class="error-text form-text text-danger"></small>
                </div>

                {{-- Foto Kerusakan --}}
                <div class="form-group">
                    <label>Foto Kerusakan</label>
                    <input type="file" name="laporan_foto" accept=".jpg,.jpeg,.png" class="form-control">
                    <small id="error-laporan_foto" class="error-text form-text text-danger"></small>
                </div>

                {{-- Tingkat Kerusakan --}}
                <div class="form-group">
                    <label>Tingkat Kerusakan</label>
                    <select name="tingkat_kerusakan" class="form-control" required>
                        <option value="">- Pilih Tingkat Kerusakan -</option>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                    <small id="error-tingkat_kerusakan" class="error-text form-text text-danger"></small>
                </div>

                {{-- Tingkat Urgensi --}}
                <div class="form-group">
                    <label>Tingkat Urgensi</label>
                    <select name="tingkat_urgensi" class="form-control" required>
                        <option value="">- Pilih Tingkat Urgensi -</option>
                        <option value="rendah">Rendah</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                    <small id="error-tingkat_urgensi" class="error-text form-text text-danger"></small>
                </div>

                {{-- Dampak Kerusakan --}}
                <div class="form-group">
                    <label>Dampak Kerusakan</label>
                    <select name="dampak_kerusakan" class="form-control" required>
                        <option value="">- Pilih Dampak Kerusakan -</option>
                        <option value="kecil">Kecil</option>
                        <option value="sedang">Sedang</option>
                        <option value="besar">Besar</option>
                    </select>
                    <small id="error-dampak_kerusakan" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Ambil ruang berdasarkan lantai
        $('select[name="lantai_id"]').on('change', function() {
            var lantaiID = $(this).val();
            var ruangSelect = $('select[name="ruang_id"]');
            var saranaSelect = $('select[name="sarana_id"]');

            ruangSelect.empty().append('<option value="">- Pilih Ruang -</option>');
            saranaSelect.empty().append('<option value="">- Pilih Sarana -</option>').prop('disabled', true);

            if (lantaiID) {
                $.ajax({
                    url: "{{ url('laporan/ajax/ruang-by-lantai') }}/" + lantaiID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                ruangSelect.append('<option value="' + value.ruang_id + '">' + value.ruang_nama + '</option>');
                            });
                            ruangSelect.prop('disabled', false);
                        } else {
                            ruangSelect.prop('disabled', true);
                            Swal.fire('Info', 'Tidak ada ruang tersedia untuk lantai ini.', 'info');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengambil data ruang.', 'error');
                    }
                });
            } else {
                ruangSelect.prop('disabled', true);
            }
        });

        // Ambil sarana berdasarkan ruang
        $('select[name="ruang_id"]').on('change', function() {
            var ruangID = $(this).val();
            var saranaSelect = $('select[name="sarana_id"]');
            saranaSelect.empty().append('<option value="">- Pilih Sarana -</option>');

            if (ruangID) {
                $.ajax({
                    url: "{{ url('laporan/ajax/sarana-by-ruang') }}/" + ruangID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                saranaSelect.append('<option value="' + value.sarana_id + '">' + value.sarana_kode + ' - ' + value.sarana_nama + ' ' + value.nomor_urut + '</option>');
                            });
                            saranaSelect.prop('disabled', false);
                        } else {
                            saranaSelect.prop('disabled', true);
                            Swal.fire('Info', 'Tidak ada sarana tersedia untuk ruang ini.', 'info');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengambil data sarana.', 'error');
                    }
                });
            } else {
                saranaSelect.prop('disabled', true);
            }
        });

        // Submit form AJAX
        $('#form-create-laporan').off('submit').on('submit', function(e) {
            e.preventDefault();
            $('.error-text').text('');
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#form-create-laporan button[type="submit"]').prop('disabled', true);
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#form-create-laporan')[0].reset();
                        $('select[name="ruang_id"]').html('<option value="">- Pilih Ruang -</option>');
                        $('select[name="sarana_id"]').html('<option value="">- Pilih Sarana -</option>');
                        if ($('#status').length) {
                            $('#status').val('').trigger('change');
                        }
                        $('#myModal').modal('hide');
                        $('#laporan-table').DataTable().ajax.reload(null, false);
                    } else {
                        $.each(response.errors, function(key, value) {
                            $('#error-' + key).text(value[0]);
                        });
                        Swal.fire('Gagal', 'Gagal menyimpan laporan. Periksa kembali isian.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                },
                complete: function() {
                    $('#form-create-laporan button[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>
