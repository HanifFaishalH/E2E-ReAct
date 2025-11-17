@extends('layout.template')

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Konten modal akan dimuat di sini -->
            </div>
        </div>
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
                            <label class="col-form-label col-sm-2">Filter Gedung:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="gedung_id" name="gedung_id">
                                    <option value="">- Pilih Gedung -</option>
                                    @foreach ($gedung as $item)
                                        <option value="{{ $item->gedung_id }}">{{ $item->gedung_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Gedung</small>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="button" class="btn btn-primary"
                                    onclick="modalAction('{{ url('gedung/create_ajax') }}')">
                                    <i class="fa fa-plus"></i> Tambah Gedung
                                </button>
                            </div>
                        </div>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_gedung">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Gedung</th>
                                        <th>Kode Gedung</th>
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
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#myModal .modal-content').html(response.html);
                        $('#myModal').modal('show');
                    } else {
                        alert('Gagal memuat data: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat memuat data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        message = 'Data tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        message = 'Kesalahan server internal.';
                    }
                    alert(message);
                    console.error('Error:', xhr);
                }
            });
        }

        var dataGedung;
        $(document).ready(function() {
            dataGedung = $('#table_gedung').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('gedung/list') }}",
                    dataType: "json",
                    type: "GET",
                    data: function(d) {
                        d.gedung_id = $('#gedung_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "gedung_id"
                    },
                    {
                        data: "gedung_nama",
                        name: "gedung_nama"
                    },
                    {
                        data: "gedung_kode",
                        name: "gedung_kode"
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

            $('#gedung_id').on('change', function() {
                dataGedung.ajax.reload();
            });

            // Tangani form submission via AJAX untuk create
            $(document).on('submit', '#form-create-gedung', function(e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#myModal').modal('hide');
                            dataGedung.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil ditambahkan!',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan data: ' + (response.message ||
                                    'Coba lagi.'),
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                        console.error('Error:', xhr);
                    }
                });
            });

            // Tangani form submission via AJAX untuk update
            $(document).on('submit', '#editGedungForm', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = "{{ route('gedung.update', ':id') }}".replace(':id', form.find(
                    'input[name="gedung_id"]').val() || form.data('id'));

                $.ajax({
                    url: url,
                    type: 'POST', // Laravel akan menangani @method('PUT') via _method
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#myModal').modal('hide');
                            dataGedung.ajax.reload(); // Refresh DataTable
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil diperbarui!',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan data: ' + (response.message ||
                                    'Coba lagi.'),
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                        console.error('Error:', xhr);
                    }
                });
            });

            // Tangani form submission via AJAX untuk delete
            $(document).on('submit', '#deleteGedungForm', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST', // Laravel menangani @method('DELETE') via _method
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#myModal').modal('hide');
                            dataGedung.ajax.reload(); // Refresh DataTable hanya jika sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil dihapus!',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data: ' + (response.message ||
                                    'Coba lagi.'),
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            message = 'Data tidak ditemukan.';
                        } else if (xhr.status === 500) {
                            message = 'Kesalahan server internal.';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                        console.error('Error:', xhr);
                    }
                });
            });
        });
    </script>
@endpush
