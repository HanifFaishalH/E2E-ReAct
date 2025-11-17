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
                        <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-info">Tambah
                            User</button>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Filter Level:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="level_id" name="level_id">
                                    <option value="">- Pilih Level -</option>
                                    @foreach ($level as $item)
                                        <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Level Pengguna</small>
                            </div>
                        </div>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>No Induk</th>
                                        <th>Nama</th>
                                        <th>Level</th>
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
        if (status === "error") {
            $('#myModal').html(
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="alert alert-danger">Gagal memuat konten. Silakan coba lagi.</div></div></div></div>'
            );
        }
        $('#myModal').modal('show');

        // Handle form update user
        $('#form-update-user').on('submit', function(e) {
            e.preventDefault();
            
            let formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#myModal').modal('hide');
                        // Reload datatable tanpa reset paging
                        dataUser.ajax.reload(null, false);
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data user berhasil diperbarui!',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Gagal memperbarui user'
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
                            text: xhr.responseJSON?.message || 'Gagal memperbarui user. Silakan coba lagi.'
                        });
                    }
                }
            });
        });

        // Handle form create user
        $('#form-create-user').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // Penting untuk FormData
                processData: false, // Penting untuk FormData
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#myModal').modal('hide');
                        // Bersihkan form
                        $('#form-create-user')[0].reset();
                        // Reload datatable
                        dataUser.ajax.reload(null, false);
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data user berhasil ditambahkan!',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Gagal menambahkan user'
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
                            text: xhr.responseJSON?.message || 'Gagal menambahkan user. Silakan coba lagi.'
                        });
                    }
                }
            });
        });

        // Handle form delete user
        $('#form-delete-user').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'DELETE',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#myModal').modal('hide');
                        dataUser.ajax.reload(null, false);
                        showSuccessAlert(response.message || 'Data user berhasil dihapus!');
                    } else {
                        showErrorAlert(response.message || 'Gagal menghapus user');
                    }
                },
                error: function(xhr) {
                    handleAjaxError(xhr, 'hapus user');
                }
            });
        });
    });
}

// Helper functions
function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
}

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: message
    });
}

function handleAjaxError(xhr, action) {
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
        let message = xhr.responseJSON?.message || `Gagal ${action}. Silakan coba lagi.`;
        if (xhr.status === 404) {
            message = 'Data tidak ditemukan.';
        } else if (xhr.status === 500) {
            message = 'User memiliki relasi dengan data lain.';
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }
}

var dataUser;
$(document).ready(function() {
    dataUser = $('#table_user').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('user/list') }}",
            dataType: "json",
            type: "GET",
            data: function(d) {
                d.level_id = $('#level_id').val();
            }
        },
        columns: [{
                data: "DT_RowIndex",
                name: "DT_RowIndex"
            },
            {
                data: "username",
                name: "username"
            },
            {
                data: "no_induk",
                name: "no_induk"
            },
            {
                data: "nama",
                name: "nama"
            },
            {
                data: "level_nama",
                name: "level_nama"
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

    $('#level_id').on('change', function() {
        dataUser.ajax.reload();
    });

    $(document).on('click', '.btn-detail', function() {
        var id = $(this).data('id');
        modalAction('/user/show/' + id);
    });

    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        modalAction('/user/edit/' + id);
    });

    $(document).on('click', '.btn-hapus', function() {
        var id = $(this).data('id');
        modalAction('/user/delete_ajax/' + id);
    });
});
    </script>
@endpush
