@extends('layout.template')
@section('content')
    {{-- Modal --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Changed to modal-md for compact width -->
            <div class="modal-content">
                {{-- Konten akan dimuat via Ajax --}}
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
                            <label for="status" class="col-form-label col-sm-2">Filter Status:</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="status" name="status">
                                    <option value="">- Semua Status -</option>
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                <small class="form-text text-muted">Pilih status laporan untuk memfilter data</small>
                            </div>
                        </div>

                        <div class="data-tables">
                            <table class="table table-bordered table-striped table-hover table-sm" id="table_laporan"
                                style="width: 100%">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>No</th>
                                        <th>ID Laporan</th> <!-- Add this column -->
                                        <th>Judul</th>
                                        <th>Sarana</th>
                                        <th>Status Laporan</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Bobot</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
        function modalAction(url = '') {
            $('#myModal .modal-content').html(
                '<div class="text-center p-4"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            $('#myModal').modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json', // Ensure we're expecting JSON response
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response:', response); // Debugging
                    if (response.status === 'success' && response.html) {
                        $('#myModal .modal-content').html(response.html);
                    } else {
                        $('#myModal .modal-content').html(
                            '<div class="alert alert-danger">' +
                            (response.message || 'Gagal memuat konten. Format respons tidak valid.') +
                            '</div>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error, xhr.responseText); // Debugging
                    let errorMsg = 'Gagal memuat konten. Silakan coba lagi.';

                    if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki akses untuk tindakan ini.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'Konten tidak ditemukan.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    $('#myModal .modal-content').html(
                        '<div class="alert alert-danger">' + errorMsg + '</div>'
                    );
                }
            });
        }

        function calculatePriority(laporanId) {
            // Show loading state in modal
            $('#myModal .modal-content').html(
                '<div class="text-center p-4"><i class="fa fa-spinner fa-spin"></i> Menghitung...</div>');
            $('#myModal').modal('show');

            $.ajax({
                url: '{{ url('laporan/kalkulasi') }}/' + laporanId,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    // Optionally disable the button to prevent multiple clicks
                    $('button[onclick*="kalkulasi/' + laporanId + '"]').prop('disabled', true);
                },
                success: function(response) {
                    if (response.status === 'success' && response.html) {
                        $('#myModal .modal-content').html(response.html);
                        // Optionally update modal title
                        $('#myModal .modal-content').prepend(
                            '<div class="modal-header"><h5 class="modal-title">Hasil Kalkulasi Prioritas</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>'
                        );
                        console.log('Bobot:', response.bobot);

                        // Reload the DataTables to reflect the new bobot value
                        $('#table_laporan').DataTable().ajax.reload(null, false);
                    } else {
                        $('#myModal .modal-content').html(
                            '<div class="alert alert-danger">' + (response.message ||
                                'Gagal memproses kalkulasi.') + '</div>'
                        );
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Terjadi kesalahan saat melakukan kalkulasi.';
                    if (xhr.status === 403) {
                        errorMsg = 'Anda tidak memiliki akses untuk melakukan kalkulasi ini.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'Laporan tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                    }
                    $('#myModal .modal-content').html(
                        '<div class="alert alert-danger">' + errorMsg + '</div>'
                    );
                    // Add a close button to the error message
                    $('#myModal .modal-content').append(
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>'
                    );
                },
                complete: function() {
                    // Re-enable the button
                    $('button[onclick*="kalkulasi/' + laporanId + '"]').prop('disabled', false);
                }
            });
        }

        $(document).ready(function() {
            let dataLaporan = $('#table_laporan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ url('laporan/list_kelola') }}",
                    data: function(d) {
                        d.status = $('#status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'laporan_id',
                        name: 'laporan_id',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'laporan_judul',
                        name: 'laporan_judul',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sarana',
                        name: 'sarana',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status_laporan',
                        name: 'status_laporan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'bobot',
                        name: 'bobot',
                        render: function(data, type, row) {
                            return data ? Math.floor(data) : '-';
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                    }
                ],
                order: [
                    [6, 'desc']
                ]
            });

            $('#status').on('change', function() {
                dataLaporan.ajax.reload();
            });
        });

        // Remove this function completely or replace with:
        $(document).on('submit', '#formDetailLaporan', function(e) {
            e.preventDefault();

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menerima laporan ini? Status akan berubah menjadi "Proses".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            console.log('Success Response:', response);
                            if (response.status === 'success') {
                                $('#myModal').modal('hide');
                                $('#table_laporan').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Laporan berhasil diterima dan status diubah menjadi Proses.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Gagal menerima laporan.',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Error Response:', xhr);
                            let errorMsg = 'Terjadi kesalahan saat menerima laporan.';
                            if (xhr.status === 419) {
                                errorMsg =
                                    'Token CSRF tidak cocok atau sesi telah kedaluwarsa. Silakan refresh halaman.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'Laporan tidak ditemukan.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#formTugaskanTeknisi', function(e) {
            e.preventDefault();

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menugaskan teknisi ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tugaskan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Success Response:', response);
                            if (response.status === 'success') {
                                $('#myModal').modal('hide');
                                $('#table_laporan').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Teknisi berhasil ditugaskan.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message ||
                                        'Gagal menugaskan teknisi.',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Error Response:', xhr);
                            let errorMsg = 'Terjadi kesalahan saat menugaskan teknisi.';
                            if (xhr.status === 419) {
                                errorMsg =
                                    'Token CSRF tidak cocok atau sesi telah kedaluwarsa. Silakan refresh halaman.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'Laporan tidak ditemukan.';
                            } else if (xhr.status === 403) {
                                errorMsg = 'Anda tidak memiliki akses untuk tindakan ini.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#formRejectLaporan', function(e) {
            e.preventDefault();

            // Konfirmasi dengan SweetAlert
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menolak laporan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Success Response:', response);
                            if (response.status === 'success') {
                                $('#myModal').modal('hide');
                                $('#table_laporan').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Laporan berhasil ditolak.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message || 'Gagal menolak laporan.',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Error Response:', xhr);
                            let errorMsg = 'Terjadi kesalahan saat menolak laporan.';
                            if (xhr.status === 419) {
                                errorMsg =
                                    'Token CSRF tidak cocok atau sesi telah kedaluwarsa. Silakan refresh halaman.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'Laporan tidak ditemukan.';
                            } else if (xhr.status === 403) {
                                errorMsg = 'Anda tidak memiliki akses untuk tindakan ini.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });

        // $(document).on('submit', '#formFinishLaporan', function (e) {
        //     e.preventDefault();

        //     if (!confirm('Apakah Anda yakin ingin menandai laporan ini sebagai selesai?')) {
        //         return;
        //     }

        //     $.ajax({
        //         url: $(this).attr('action'),
        //         type: 'POST',
        //         data: $(this).serialize(),
        //         dataType: 'json',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function (response) {
        //             console.log('Success Response:', response);
        //             if (response.status === 'success') {
        //                 $('#myModal').modal('hide');
        //                 alert(response.message || 'Laporan berhasil diselesaikan.');
        //                 $('#table_laporan').DataTable().ajax.reload(null, false);
        //             } else {
        //                 alert(response.message || 'Gagal menandai laporan sebagai selesai.');
        //             }
        //         },
        //         error: function (xhr) {
        //             console.error('Error Response:', xhr);
        //             let errorMsg = 'Terjadi kesalahan saat menandai laporan sebagai selesai.';
        //             if (xhr.status === 419) {
        //                 errorMsg =
        //                     'Token CSRF tidak cocok atau sesi telah kedaluwarsa. Silakan refresh halaman.';
        //             } else if (xhr.status === 404) {
        //                 errorMsg = 'Laporan tidak ditemukan.';
        //             } else if (xhr.status === 403) {
        //                 errorMsg = 'Anda tidak memiliki akses untuk tindakan ini.';
        //             } else if (xhr.responseJSON && xhr.responseJSON.message) {
        //                 errorMsg = xhr.responseJSON.message;
        //             }
        //             alert(errorMsg);
        //         }
        //     });
        // });

        $(document).on('click', '#finishLaporanButton', function(e) {
            e.preventDefault();
            let laporanId = $(this).data('id');
            let finishUrl = '{{ url('laporan/finish_form') }}/' + laporanId;

            $('#myModal .modal-content').html(
                '<div class="text-center p-4"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            $('#myModal').modal('show');

            $.ajax({
                url: finishUrl,
                type: 'GET',
                success: function(response) {
                    $('#myModal .modal-content').html(response);
                },
                error: function(xhr) {
                    let errorMsg = 'Terjadi kesalahan saat memuat formulir.';
                    if (xhr.status === 404) {
                        errorMsg = 'Laporan tidak ditemukan.';
                    }
                    $('#myModal .modal-content').html('<div class="alert alert-danger">' + errorMsg +
                        '</div>');
                }
            });
        });

        $(document).on('submit', '#formFinishLaporan', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let finishUrl = $(this).attr('action');

            // Konfirmasi dengan SweetAlert (opsional, tambahkan jika diperlukan)
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyelesaikan laporan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: finishUrl,
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#myModal').modal('hide');
                                $('#table_laporan').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message ||
                                        'Laporan berhasil diselesaikan.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message ||
                                        'Gagal menyelesaikan laporan.',
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
