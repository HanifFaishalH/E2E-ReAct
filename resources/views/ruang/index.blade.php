@extends('layout.template')

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
        <!-- Konten modal akan dimuat di sini -->
    </div>

    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ $page->title ?? 'Data Ruang' }}</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Filter Lantai:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="lantai_id" name="lantai_id">
                                    <option value="">- Pilih Lantai -</option>
                                    @foreach ($lantai as $l)
                                        <option value="{{ $l->lantai_id }}">{{ $l->lantai_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Lantai Ruangan</small>
                            </div>
                        </div>

                            <div class="form-group row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary"
                                    onclick="modalAction('{{ url('/ruang/create_ajax') }}')">
                                    <i class="fa fa-plus"></i> Tambah Ruang
                                </button>
                            </div>
                        </div>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="ruang-table">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Ruang</th>
                                        <th>Nama Ruang</th>
                                        <th>Tipe Ruang</th>
                                        <th>Lantai</th>
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
    <!-- Add any additional CSS if needed -->
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

                $('#form-create-ruang').on('submit', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation(); // Tambahkan ini untuk mencegah multiple submission

                    let form = $(this);
                    let url = form.attr('action');
                    let data = form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        beforeSend: function() {
                            // Tampilkan loading indicator jika perlu
                            $('.modal-footer button').prop('disabled', true);
                        },
                        complete: function() {
                            $('.modal-footer button').prop('disabled', false);
                        },
                        success: function(response) {
                            $('#myModal').modal('hide');
                            $('#ruang-table').DataTable().ajax.reload(null, false);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil disimpan!',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            form.trigger("reset");
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessage = '';

                                $.each(errors, function(key, value) {
                                    errorMessage += value[0] + '<br>';
                                });

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal',
                                    html: errorMessage,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan pada server.',
                                });
                            }
                        }
                    });
                    return false; // Mencegah form submit biasa
                });

                // Handle form update ruang
                $('#form-update-ruang').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const method = form.find('input[name="_method"]').val() || 'POST';

                    $.ajax({
                        url: form.attr('action'),
                        type: method,
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataRuang.ajax.reload(null, false); // Reload tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Data ruang berhasil diperbarui!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal update ruang: ' + (response.message ||
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
                                    text: 'Gagal update ruang. Silakan coba lagi.'
                                });
                            }
                        }
                    });
                });

                // Handle form delete ruang
                $('#form-delete-ruang').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const method = 'DELETE';

                    $.ajax({
                        url: form.attr('action'),
                        type: method,
                        data: form.serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                dataRuang.ajax.reload(null, false); // Reload tabel
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Data ruang berhasil dihapus!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menghapus ruang: ' + (response
                                        .message || 'Coba lagi.'),
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Gagal menghapus ruang. Silakan coba lagi.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.status === 404) {
                                message = 'Data ruang tidak ditemukan.';
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

        var dataRuang;
        $(document).ready(function() {
            dataRuang = $('#ruang-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('ruang/list') }}",
                    dataType: "json",
                    type: "GET",
                    data: function(d) {
                        d.lantai_id = $('#lantai_id').val();
                    },
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
                        data: "ruang_kode",
                        name: "ruang_kode"
                    },
                    {
                        data: "ruang_nama",
                        name: "ruang_nama"
                    },
                    {
                        data: "ruang_tipe",
                        name: "ruang_tipe"
                    },
                    {
                        data: "lantai",
                        name: "lantai.lantai_nama",
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

            $('#lantai_id').change(function() {
                console.log('Selected lantai_id:', $(this).val());
                dataRuang.ajax.reload(null, false);
            });
        });

        // Delete function using modal
        function deleteRuang(id) {
            modalAction('{{ url('/ruang/delete_ajax') }}/' + id);
        }
    </script>
@endpush
