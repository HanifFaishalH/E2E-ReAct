<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Detail Lantai: {{ $lantai->lantai_nama }}</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Nama Gedung:</strong> {{ $lantai->gedung->gedung_nama ?? '-' }}</p>

            <h6 class="mt-3">Daftar Ruangan di Lantai Ini:</h6>
            @if($lantai->ruang->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruang</th>
                                <th>Kode Ruang</th>
                                <th>Tipe Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lantai->ruang as $index => $ruang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ruang->ruang_nama }}</td>
                                    <td>{{ $ruang->ruang_kode ?? '-' }}</td>
                                    <td>{{ $ruang->ruang_tipe ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Tidak ada ruang di lantai ini.</p>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
