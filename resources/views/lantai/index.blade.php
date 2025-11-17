@extends('layout.template')

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
        <!-- Konten modal detail akan dimuat di sini -->
    </div>

    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ $page->title ?? 'Data Lantai' }}</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <button onclick="modalAction('{{ url('/lantai/create_ajax') }}')" class="btn btn-info mb-3">Tambah
                            Lantai</button>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="lantai-table">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lantai</th>
                                        <th>Gedung</th>
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
            $('#myModal').load(url, function(response, status, xhr) {
                if (status == "error") {
                    $('#myModal').html(
                        '<div class="alert alert-danger">Gagal memuat konten. Silakan coba lagi.</div>');
                }
                $('#myModal').modal('show');

                $('#form-create-ruang').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let url = form.attr('action');
                    let data = form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataLantai.ajax.reload(null, false);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message || 'Data berhasil disimpan!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Data gagal disimpan!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            let msg = '';

                            if (errors) {
                                Object.values(errors).forEach(arr => {
                                    arr.forEach(e => {
                                        msg += e + '<br>';
                                    });
                                });
                            } else {
                                msg = 'Terjadi kesalahan saat mengirim data.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: msg
                            });
                        }
                    });
                });

                // Ajax Update Lantai
                $('#form-update-lantai').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let url = form.attr('action');
                    let data = form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST', // karena di route pakai PUT method spoofing
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataLantai.ajax.reload(null,
                                    false
                                ); // pastikan dataLantai adalah variabel datatable kamu
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data lantai berhasil diperbarui.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal memperbarui data: ' + (response
                                        .message ||
                                        'Terjadi kesalahan.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON?.errors;
                            let msg = 'Terjadi kesalahan pada input:<br>';
                            if (errors) {
                                Object.values(errors).forEach(arr => {
                                    arr.forEach(e => {
                                        msg += '- ' + e + '<br>';
                                    });
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    html: msg
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan: ' + (xhr.responseText ||
                                        'Coba lagi.')
                                });
                            }
                        }
                    });
                });

                // Ajax Hapus Lantai
                $('#form-delete-lantai').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let url = form.attr('action');
                    let data = form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataLantai.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data lantai berhasil dihapus.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menghapus data: ' + (response
                                        .message || 'Coba lagi.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan: ' + (xhr.responseText ||
                                'Coba lagi.');
                            if (xhr.status === 404) {
                                message = 'Data lantai tidak ditemukan.';
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
        }

        var dataLantai;
        $(document).ready(function() {
            dataLantai = $('#lantai-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('lantai/list') }}",
                    dataType: "json",
                    type: "GET",
                    error: function(xhr) {
                        console.error('DataTable AJAX error:', xhr.responseText);
                        alert('Gagal memuat data tabel. Silakan coba lagi.');
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "lantai_nama",
                        name: "lantai_nama"
                    },
                    {
                        data: "gedung",
                        name: "gedung",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        function showDetail(id) {
            let url = `{{ url('/lantai/show_ajax/') }}/${id}`;
            modalAction(url);
        }
    </script>
@endpush
