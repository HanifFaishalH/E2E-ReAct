<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Changed to modal-md for compact width -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Laporan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditLaporan" method="POST"
                    action="{{ url('/laporan/' . $laporan->laporan_id . '/update_ajax') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="laporan_id" value="{{ $laporan->laporan_id }}">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Gedung</label>
                        <div class="col-sm-9">
                            <select name="gedung_id" id="gedung_id" class="form-control" required>
                                <option value="">Pilih Gedung</option>
                                @foreach ($gedung as $g)
                                    <option value="{{ $g->gedung_id }}"
                                        {{ $laporan->gedung_id == $g->gedung_id ? 'selected' : '' }}>
                                        {{ $g->gedung_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Lantai</label>
                        <div class="col-sm-9">
                            <select name="lantai_id" id="lantai_id" class="form-control" required>
                                <option value="">Pilih Lantai</option>
                                @foreach ($lantai as $l)
                                    <option value="{{ $l->lantai_id }}"
                                        {{ $laporan->lantai_id == $l->lantai_id ? 'selected' : '' }}>
                                        {{ $l->lantai_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ruang</label>
                        <div class="col-sm-9">
                            <select name="ruang_id" id="ruang_id" class="form-control" required>
                                <option value="">Pilih Ruang</option>
                                @foreach ($ruang as $r)
                                    <option value="{{ $r->ruang_id }}"
                                        {{ $laporan->ruang_id == $r->ruang_id ? 'selected' : '' }}>{{ $r->ruang_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Sarana</label>
                        <div class="col-sm-9">
                            <select name="sarana_id" id="sarana_id" class="form-control" required>
                                <option value="">Pilih Sarana</option>
                                @foreach ($sarana as $s)
                                    <option value="{{ $s->sarana_id }}"
                                        {{ $laporan->sarana_id == $s->sarana_id ? 'selected' : '' }}>
                                        {{ $s->barang->barang_nama ?? 'Sarana #' . $s->sarana_id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Judul Laporan</label>
                        <div class="col-sm-9">
                            <input type="text" name="laporan_judul" class="form-control"
                                value="{{ $laporan->laporan_judul }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Foto Laporan</label>
                        <div class="col-sm-9">
                            <input type="file" name="laporan_foto" class="form-control-file" accept="image/*">
                            @if ($laporan->laporan_foto)
                                <p>Aktual: <a href="{{ asset($laporan->laporan_foto) }}" target="_blank">Lihat Foto</a>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tingkat Kerusakan</label>
                        <div class="col-sm-9">
                            <select name="tingkat_kerusakan" class="form-control" required>
                                <option value="rendah" {{ $laporan->tingkat_kerusakan == 'rendah' ? 'selected' : '' }}>
                                    Rendah</option>
                                <option value="sedang" {{ $laporan->tingkat_kerusakan == 'sedang' ? 'selected' : '' }}>
                                    Sedang</option>
                                <option value="tinggi" {{ $laporan->tingkat_kerusakan == 'tinggi' ? 'selected' : '' }}>
                                    Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tingkat Urgensi</label>
                        <div class="col-sm-9">
                            <select name="tingkat_urgensi" class="form-control" required>
                                <option value="rendah" {{ $laporan->tingkat_urgensi == 'rendah' ? 'selected' : '' }}>
                                    Rendah</option>
                                <option value="sedang" {{ $laporan->tingkat_urgensi == 'sedang' ? 'selected' : '' }}>
                                    Sedang</option>
                                <option value="tinggi" {{ $laporan->tingkat_urgensi == 'tinggi' ? 'selected' : '' }}>
                                    Tinggi</option>
                                <option value="kritis" {{ $laporan->tingkat_urgensi == 'kritis' ? 'selected' : '' }}>
                                    Kritis</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Frekuensi Penggunaan</label>
                        <div class="col-sm-9">
                            <select name="frekuensi_penggunaan" class="form-control" required>
                                <option value="harian"
                                    {{ $laporan->frekuensi_penggunaan == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan"
                                    {{ $laporan->frekuensi_penggunaan == 'mingguan' ? 'selected' : '' }}>Mingguan
                                </option>
                                <option value="bulanan"
                                    {{ $laporan->frekuensi_penggunaan == 'bulanan' ? 'selected' : '' }}>Bulanan
                                </option>
                                <option value="tahunan"
                                    {{ $laporan->frekuensi_penggunaan == 'tahunan' ? 'selected' : '' }}>Tahunan
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Operasional</label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal_operasional" class="form-control"
                                value="{{ $laporan->tanggal_operasional }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        .modal-dialog {
            max-width: 600px;
            /* Set desired width */
            margin: 1.75rem auto;
            /* Center modal */
        }

        .modal-content {
            background-color: #fff;
            /* Solid white background */
            border-radius: 0.3rem;
            /* Bootstrap default border-radius */
        }

        .modal {
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Update lantai when gedung changes
            $('#gedung_id').on('change', function() {
                var gedung_id = $(this).val();
                $('#lantai_id').empty().append('<option value="">Pilih Lantai</option>');
                $('#ruang_id').empty().append('<option value="">Pilih Ruang</option>');
                $('#sarana_id').empty().append('<option value="">Pilih Sarana</option>');
                if (gedung_id) {
                    $.ajax({
                        url: '{{ url('/laporan/ajax/lantai') }}/' + gedung_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#lantai_id').append('<option value="' + value
                                    .lantai_id + '">' + value.lantai_nama +
                                    '</option>');
                            });
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data lantai: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });

            // Update ruang and sarana when lantai changes
            $('#lantai_id').on('change', function() {
                var lantai_id = $(this).val();
                $('#ruang_id').empty().append('<option value="">Pilih Ruang</option>');
                $('#sarana_id').empty().append('<option value="">Pilih Sarana</option>');
                if (lantai_id) {
                    $.ajax({
                        url: '{{ url('/laporan/ajax/ruang-sarana') }}/' + lantai_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data.ruang, function(key, value) {
                                $('#ruang_id').append('<option value="' + value
                                    .ruang_id + '">' + value.ruang_nama +
                                    '</option>');
                            });
                            $.each(data.sarana, function(key, value) {
                                $('#sarana_id').append('<option value="' + value
                                    .sarana_id + '">' + (value.barang ? value.barang
                                        .barang_nama : 'Sarana #' + value.sarana_id
                                        ) + '</option>');
                            });
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data ruang dan sarana: ' + xhr.responseJSON
                                .message);
                        }
                    });
                }
            });

            // Handle form submission
            $('#formEditLaporan').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message || 'Laporan berhasil diperbarui.');
                            $('#myModal').modal('hide');
                            $('#table_laporan').DataTable().ajax.reload();
                        } else {
                            alert('Gagal memperbarui laporan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors || {};
                        var errorMessage = 'Gagal memperbarui laporan:\n';
                        $.each(errors, function(key, value) {
                            errorMessage += value + '\n';
                        });
                        alert(errorMessage || 'Terjadi kesalahan saat memperbarui laporan.');
                    }
                });
            });
        });
    </script>
@endpush
