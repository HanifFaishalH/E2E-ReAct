<div class="modal-header">
    <h5 class="modal-title">Tugaskan Teknisi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="formTugaskanTeknisi" action="{{ url('laporan/tugaskan_teknisi/' . $laporan->laporan_id) }}" method="POST">
        @csrf
        <div class="row">
            <!-- Left Column: Barang Name and Picture -->
            <div class="col-md-6">
                <!-- Barang Name -->
                <div class="mb-3">
                    <h6>Nama Barang: {{ $laporan->sarana->barang->barang_nama ?? 'Tidak tersedia' }}</h6>
                </div>
                <!-- Picture -->
                <div class="mb-3">
                    @if ($laporan->laporan_foto)
                        <img src="{{ asset($laporan->laporan_foto) }}" alt="Foto Laporan" class="img-fluid"
                            style="max-width: 100%; max-height: 200px; object-fit: cover;">
                    @else
                        <p>Tidak ada foto tersedia</p>
                    @endif
                </div>
            </div>
            <!-- Right Column: Technician Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="teknisi_id">Pilih Teknisi</label>
                    <select name="teknisi_id" id="teknisi_id" class="form-control" required>
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach ($teknisi as $t)
                            <option value="{{ $t->teknisi_id }}">{{ $t->user->username }} ({{ $t->keahlian }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Tugaskan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    </form>
</div>