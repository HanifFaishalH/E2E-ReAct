@extends('layout.template')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">AHP & SAW Analysis Tables</h4>
                    
                    <div id="weightsAccordion" class="according">
                        <!-- Table 1: Comparison Table of Weights -->
                        <div class="card">
                            <div class="card-header">
                                <a class="card-link" data-toggle="collapse" href="#collapseWeights">
                                    <i class="fa fa-table me-2"></i>
                                    Comparison Table of Weights
                                </a>
                            </div>
                            <div id="collapseWeights" class="collapse show" data-parent="#weightsAccordion">
                                <div class="card-body">
                                    <table class="table table-bordered table-sm compact-table">
                                        <thead>
                                            <tr>
                                                <th>Factor</th>
                                                <th>Value</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Kerusakan</td>
                                                <td>tinggi</td>
                                                <td>3</td>
                                            </tr>
                                            <tr>
                                                <td>Urgensi</td>
                                                <td>sedang</td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>Frekuensi</td>
                                                <td>harian</td>
                                                <td>4</td>
                                            </tr>
                                            <tr>
                                                <td>Dampak</td>
                                                <td>besar</td>
                                                <td>3</td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah Laporan</td>
                                                <td>5</td>
                                                <td>0.9</td>
                                            </tr>
                                            <tr>
                                                <td>Usia</td>
                                                <td>1826</td>
                                                <td>0.5</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Table 2: Comparison Table of AHP Weights -->
                        <div class="card">
                            <div class="card-header">
                                <a class="collapsed card-link" data-toggle="collapse" href="#collapseAHPWeights">
                                    <i class="fa fa-balance-scale me-2"></i>
                                    Comparison Table of AHP Weights
                                </a>
                            </div>
                            <div id="collapseAHPWeights" class="collapse" data-parent="#weightsAccordion">
                                <div class="card-body">
                                    <table class="table table-bordered table-sm compact-table">
                                        <thead>
                                            <tr>
                                                <th>Factor</th>
                                                <th>Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Kerusakan</td>
                                                <td>{{ number_format(0.273, 3) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Urgensi</td>
                                                <td>{{ number_format(0.273, 3) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Frekuensi</td>
                                                <td>{{ number_format(0.154, 3) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Dampak</td>
                                                <td>{{ number_format(0.089, 3) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah Laporan</td>
                                                <td>{{ number_format(0.154, 3) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Usia</td>
                                                <td>{{ number_format(0.056, 3) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Table 3: AHP Pairwise Comparison Matrix -->
                        <div class="card">
                            <div class="card-header">
                                <a class="collapsed card-link" data-toggle="collapse" href="#collapsePairwise">
                                    <i class="fa fa-th me-2"></i>
                                    AHP Pairwise Comparison Matrix
                                </a>
                            </div>
                            <div id="collapsePairwise" class="collapse" data-parent="#weightsAccordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm compact-table">
                                            <thead>
                                                <tr>
                                                    <th>Criteria</th>
                                                    <th>Kerusakan</th>
                                                    <th>Urgensi</th>
                                                    <th>Frekuensi</th>
                                                    <th>Dampak</th>
                                                    <th>Jumlah Laporan</th>
                                                    <th>Usia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Kerusakan</strong></td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(3, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(4, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Urgensi</strong></td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(3, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(4, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Frekuensi</strong></td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(3, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dampak</strong></td>
                                                    <td>{{ number_format(0.33, 2) }}</td>
                                                    <td>{{ number_format(0.33, 2) }}</td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jumlah Laporan</strong></td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(2, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                    <td>{{ number_format(3, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Usia</strong></td>
                                                    <td>{{ number_format(0.25, 2) }}</td>
                                                    <td>{{ number_format(0.25, 2) }}</td>
                                                    <td>{{ number_format(0.33, 2) }}</td>
                                                    <td>{{ number_format(0.5, 2) }}</td>
                                                    <td>{{ number_format(0.33, 2) }}</td>
                                                    <td>{{ number_format(1, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table 4: AHP and SAW Calculation Results -->
                        <div class="card">
                            <div class="card-header">
                                <a class="collapsed card-link" data-toggle="collapse" href="#collapseResults">
                                    <i class="fa fa-calculator me-2"></i>
                                    AHP and SAW Calculation Results
                                </a>
                            </div>
                            <div id="collapseResults" class="collapse" data-parent="#weightsAccordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm compact-table">
                                            <thead>
                                                <tr>
                                                    <th>Factor</th>
                                                    <th>Value</th>
                                                    <th>Score</th>
                                                    <th>AHP Weight</th>
                                                    <th>AHP Contribution</th>
                                                    <th>SAW Weight</th>
                                                    <th>SAW Contribution</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Kerusakan</td>
                                                    <td>tinggi</td>
                                                    <td>3</td>
                                                    <td>{{ number_format(0.273, 3) }}</td>
                                                    <td>{{ number_format(0.819, 3) }}</td>
                                                    <td>{{ number_format(0.2, 2) }}</td>
                                                    <td>{{ number_format(0.6, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Urgensi</td>
                                                    <td>sedang</td>
                                                    <td>2</td>
                                                    <td>{{ number_format(0.273, 3) }}</td>
                                                    <td>{{ number_format(0.546, 3) }}</td>
                                                    <td>{{ number_format(0.2, 2) }}</td>
                                                    <td>{{ number_format(0.4, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Frekuensi</td>
                                                    <td>harian</td>
                                                    <td>4</td>
                                                    <td>{{ number_format(0.154, 3) }}</td>
                                                    <td>{{ number_format(0.616, 3) }}</td>
                                                    <td>{{ number_format(0.2, 2) }}</td>
                                                    <td>{{ number_format(0.8, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Dampak</td>
                                                    <td>besar</td>
                                                    <td>3</td>
                                                    <td>{{ number_format(0.089, 3) }}</td>
                                                    <td>{{ number_format(0.267, 3) }}</td>
                                                    <td>{{ number_format(0.1, 2) }}</td>
                                                    <td>{{ number_format(0.3, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Laporan</td>
                                                    <td>5</td>
                                                    <td>0.9</td>
                                                    <td>{{ number_format(0.154, 3) }}</td>
                                                    <td>{{ number_format(0.1386, 3) }}</td>
                                                    <td>{{ number_format(0.2, 2) }}</td>
                                                    <td>{{ number_format(0.18, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Usia</td>
                                                    <td>1826</td>
                                                    <td>0.5</td>
                                                    <td>{{ number_format(0.056, 3) }}</td>
                                                    <td>{{ number_format(0.028, 3) }}</td>
                                                    <td>{{ number_format(0.1, 2) }}</td>
                                                    <td>{{ number_format(0.05, 3) }}</td>
                                                </tr>
                                                <tr class="table-info">
                                                    <td colspan="4"><strong>Total</strong></td>
                                                    <td><strong>{{ number_format(2.4146, 3) }}</strong></td>
                                                    <td></td>
                                                    <td><strong>{{ number_format(2.33, 3) }}</strong></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td colspan="4"><strong>Final Bobot</strong></td>
                                                    <td colspan="3"><strong>{{ number_format(237.23, 2) }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .compact-table {
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.3rem;
            border-width: 1px;
            text-align: center;
            vertical-align: middle;
        }

        .compact-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .compact-table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Accordion styling to match the template */
        .according {
            margin-bottom: 20px;
        }

        .according .card {
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
        }

        .according .card-header {
            padding: 0;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .according .card-link {
            display: block;
            padding: 0.75rem 1.25rem;
            color: #495057;
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }

        .according .card-link:hover {
            color: #007bff;
            text-decoration: none;
        }

        .according .card-link::after {
            content: '\f107';
            font-family: 'FontAwesome';
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.2s ease;
        }

        .according .card-link.collapsed::after {
            transform: translateY(-50%) rotate(-90deg);
        }

        .according .card-body {
            padding: 1.25rem;
        }

        /* Table responsive improvements */
        .table-responsive {
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Highlight important rows */
        .table-info {
            background-color: #d1ecf1 !important;
        }

        .table-success {
            background-color: #d4edda !important;
        }

        /* Icon spacing */
        .me-2 {
            margin-right: 8px;
        }

        /* Header title styling */
        .header-title {
            margin-bottom: 1.5rem;
            color: #495057;
            font-weight: 600;
        }
    </style>
@endpush

@push('js')
    <script>
        // No additional JavaScript needed - Bootstrap handles the accordion functionality
        $(document).ready(function() {
            // Optional: Add any custom accordion behavior here if needed
        });
    </script>
@endpush