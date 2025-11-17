@extends('layout.template')

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
        <!-- Konten modal akan dimuat di sini -->
    </div>

    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ $page->title }}</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Filter Kategori:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="kategori_id" name="kategori_id">
                                    <option value="">- Pilih Kategori -</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Barang</small>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="button" class="btn btn-primary"
                                    onclick="modalAction('{{ url('barang/create_ajax') }}')">
                                    <i class="fa fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                        </div>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function(response, status, xhr) {
                if (status === "error") {
                    $('#myModal').html(
                        '<div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="alert alert-danger">Gagal memuat konten. Silakan coba lagi.</div></div></div></div>'
                    );
                }
                $('#myModal').modal('show');

                // Handle form update barang
                $('#form-update-barang').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'PUT',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataBarang.ajax.reload(null, false); // Reload tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Data barang berhasil diperbarui!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal update barang: ' + (response.message ||
                                        'Coba lagi.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMsg = '';
                                $.each(errors, function(key, value) {
                                    errorMsg += `- ${value}<br>`;
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
                                    text: 'Gagal update barang. Silakan coba lagi.'
                                });
                            }
                        }
                    });
                });

                // Handle form create barang
                $('#form-create-barang').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataBarang.ajax.reload(null, false); // Reload tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Data barang berhasil ditambahkan!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal tambah barang: ' + (response.message ||
                                        'Coba lagi.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMsg = '';
                                $.each(errors, function(key, value) {
                                    errorMsg += `- ${value}<br>`;
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
                                    text: 'Gagal tambah barang. Silakan coba lagi.'
                                });
                            }
                        }
                    });
                });

                // Handle form delete barang
                $('#form-delete-barang').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'DELETE',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataBarang.ajax.reload(null, false); // Reload tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Data barang berhasil dihapus!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menghapus barang: ' + (response
                                        .message || 'Coba lagi.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Gagal menghapus barang. Silakan coba lagi.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.status === 404) {
                                message = 'Data barang tidak ditemukan.';
                            } else if (xhr.status === 500) {
                                message = 'Kesalahan server internal.';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                });
            });
        };

        var dataBarang;
        $(document).ready(function() {
            dataBarang = $('#table_barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('barang/list') }}",
                    dataType: "json",
                    type: "GET",
                    data: function(d) {
                        d.kategori_id = $('#kategori_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "barang_id"
                    },
                    {
                        data: "barang_nama",
                        name: "barang_nama"
                    },
                    {
                        data: "kategori_nama",
                        name: "kategori_nama"
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ]
            });

            $('#kategori_id').on('change', function() {
                dataBarang.ajax.reload();
            });
        });
    </script>
@endpush
