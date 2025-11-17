@extends('layout.template')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container py-4">
        <h2 class="mb-4">Kelola Periode</h2>

        <!-- Filter Section -->
        <div class="card-body">
            <form id="filter-form" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter-tahun" class="form-label">Tahun</label>
                    <select class="form-select select2" id="filter-tahun" name="tahun" style="width: 100%;">
                        <option value="">Semua Tahun</option>
                        @foreach ($listTahun as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select select2" style="width: 100%;">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="barang" class="form-label">Barang</label>
                    <select name="barang" id="barang" class="form-select select2" style="width: 100%;">
                        <option value="">Semua Barang</option>
                        @foreach($barangList as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill" id="btn-filter">
                        <span class="btn-text">Tampilkan</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                    <a id="btn-export-pdf" href="#" class="btn btn-danger flex-fill">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </form>
        </div>


        <!-- Statistik Utama -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik Umum</h5>
            </div>
            <div class="card-body">
                <div id="statistik-utama">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Detail -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Laporan per Bulan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-detail" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Jumlah Laporan</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0">Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            let isLoading = false;
            let chartInstance = null;

            function showLoading() {
                isLoading = true;
                $('#btn-filter .btn-text').text('Loading...');
                $('#btn-filter .spinner-border').removeClass('d-none');
                $('#btn-filter').prop('disabled', true);
            }

            function hideLoading() {
                isLoading = false;
                $('#btn-filter .btn-text').text('Tampilkan');
                $('#btn-filter .spinner-border').addClass('d-none');
                $('#btn-filter').prop('disabled', false);
            }

            function renderChart(labels, data) {
                const canvas = document.getElementById('chartTotalLaporan');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Jumlah' }
                            }
                        }
                    }
                });
            }

            function fetchData() {
                if (isLoading) return;
                showLoading();

                const filterData = {
                    tahun: $('#tahun').val(),
                    bulan: $('#bulan').val(),
                    barang: $('#barang').val()
                };

                $.ajax({
                    url: "{{ route('laporan.periode.data') }}",
                    method: "GET",
                    data: filterData,
                    success: function (response) {
                        try {
                            $('#statistik-utama').html(response.statistik_html);
                            renderChart(response.chart.labels, response.chart.data);

                            let tbody = '';
                            let totalLaporan = 0;
                            response.tabel.forEach(item => totalLaporan += parseInt(item.jumlah));

                            if (response.tabel.length > 0) {
                                response.tabel.forEach((item, index) => {
                                    const persentase = totalLaporan > 0 ?
                                        ((parseInt(item.jumlah) / totalLaporan) * 100).toFixed(1) : '0.0';
                                    tbody += `<tr>
                                                                                    <td>${index + 1}</td>
                                                                                    <td>${item.tahun}</td>
                                                                                    <td>${item.bulan}</td>
                                                                                    <td><span class="badge bg-primary">${item.jumlah}</span></td>
                                                                                    <td>${persentase}%</td>
                                                                                </tr>`;
                                });
                            } else {
                                tbody = `<tr><td colspan="5" class="text-center text-muted">
                                                                                        <i class="fa fa-info-circle"></i> Tidak ada data untuk filter yang dipilih
                                                                                    </td></tr>`;
                            }

                            $('#tabel-detail tbody').html(tbody);
                            showNotification('Data berhasil dimuat', 'success');
                        } catch (error) {
                            console.error(error);
                            showError('Gagal memproses data response');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", xhr, status, error);
                        let errorMessage = 'Gagal memuat data.';
                        if (xhr.status === 404) errorMessage = 'Endpoint tidak ditemukan.';
                        else if (xhr.status === 500) errorMessage = 'Terjadi kesalahan server.';
                        else if (xhr.status === 0) errorMessage = 'Tidak dapat terhubung ke server.';
                        else if (status === 'timeout') errorMessage = 'Request timeout.';

                        showError(`${errorMessage} (${xhr.status})`);
                    },
                    complete: hideLoading
                });
            }

            function showError(message) {
                $('#statistik-utama').html(`
                                                                <div class="alert alert-danger">
                                                                    <i class="fa fa-exclamation-triangle"></i> ${message}
                                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="fetchData()">
                                                                        <i class="fa fa-refresh"></i> Coba Lagi
                                                                    </button>
                                                                </div>
                                                            `);
                $('#tabel-detail tbody').html(`<tr>
                                                                <td colspan="5" class="text-center text-danger">
                                                                    <i class="fa fa-exclamation-triangle"></i> ${message}
                                                                </td>
                                                            </tr>`);
            }

            function showNotification(message, type = 'info') {
                const notification = $(`
                                                                <div class="alert alert-${type} alert-dismissible fade show position-fixed"
                                                                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                                                                    <i class="fa fa-check-circle"></i> ${message}
                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                                </div>
                                                            `);

                $('body').append(notification);
                setTimeout(() => notification.fadeOut(() => notification.remove()), 3000);
            }

            $('#filter-form').on('submit', function (e) {
                e.preventDefault();
                fetchData();
            });

            $('#tahun, #bulan, #barang').on('change', function () {
                fetchData();
            });

            fetchData();
            window.fetchData = fetchData;
        });
        function buildExportUrl() {
            const tahun = $('#tahun').val();
            const bulan = $('#bulan').val();
            const barang = $('#barang').val();

            let params = new URLSearchParams();
            if (tahun) params.append('tahun', tahun);
            if (bulan) params.append('bulan', bulan);
            if (barang) params.append('barang', barang);

            return `{{ route('laporan.periode.export.pdf') }}?${params.toString()}`;
        }

        $('#filter-form').on('change', function () {
            $('#btn-export-pdf').attr('href', buildExportUrl());
        });
        $(document).ready(() => {
            $('#btn-export-pdf').attr('href', buildExportUrl());
        });

        //dropdown
        $(document).ready(function () {
            $('.select2').select2({
                width: 'resolve', 
                allowClear: true  
            });
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush