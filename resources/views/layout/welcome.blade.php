@extends('layout.template')

@section('content')
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

                    {{-- Dashboard berdasarkan role --}}
                    @if ($user->level_id == 1)
                        {{-- ADMIN DASHBOARD --}}
                        <h5>Halo, {{ $user->nama }}</h5>
                        <br>
                        <h1>Kelola</h1>
                        <div class="row mt-4">
                            {{-- Kelola Level --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/level') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-primary text-white py-4">
                                                <i class="fa fa-list-alt fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Level</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola Periode --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/laporan/periode') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-primary text-white py-4">
                                                <i class="fa fa-calendar fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Periode</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola Pengguna --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/user') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-primary text-white py-4">
                                                <i class="fa fa-users fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Pengguna</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola Prioritas --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/prioritas') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-primary text-white py-4">
                                                <i class="fa fa-exclamation-circle fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Prioritas</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <br>
                        <h1>Sarana dan Prasarana</h1>
                        <div class="row mt-4">
                            {{-- Kelola Lantai --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/lantai') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-secondary text-white py-4">
                                                <i class="fa fa-building fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Lantai</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            {{-- Kelola Ruang --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/ruang') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-secondary text-white py-4">
                                                <i class="fa fa-home fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Ruang</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola Prasarana --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/barang') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-secondary text-white py-4">
                                                <i class="fa fa-cubes fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Barang</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola gedung --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/gedung') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-secondary text-white py-4">
                                                <i class="fa fa-university fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Gedung</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- Kelola Sarana --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <a href="{{ url('/sarana') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow" style="cursor:pointer;">
                                        <div class="card-body text-center p-3">
                                            <div class="bg-secondary text-white py-4">
                                                <i class="fa fa-cubes fa-3x"></i>
                                            </div>
                                            <div class="bg-light text-dark pt-3 pb-2">
                                                <h4 class="card-title mb-0">Kelola Sarana</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    @elseif ($user->level_id == 2 || $user->level_id == 3 || $user->level_id == 4)
                        {{-- USER DASHBOARD --}}
                        <h5 class="font-weight-bold">Halo, {{ $user->nama }}</h5>
                        <div class="row mt-4">
                            {{-- Total Laporan --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-primary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-list-alt fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Total Laporan</h6>
                                            <h3 class="card-text mb-0">{{ $total }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ditolak --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-primary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa c fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Total Laporan</h6>
                                            <h3 class="card-text mb-0">{{ $total }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Pending --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-secondary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-clock fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Pending</h6>
                                            <h3 class="card-text mb-0">{{ $pending }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sedang Diproses --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-info h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-hourglass-half fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Sedang Diproses</h6>
                                            <h3 class="card-text mb-0">{{ $diproses }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Dikerjakan --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-warning h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-tools fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Dikerjakan</h6>
                                            <h3 class="card-text mb-0">{{ $dikerjakan }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Selesai --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-success h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-check-circle fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Selesai</h6>
                                            <h3 class="card-text mb-0">{{ $selesai }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ditolak --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-danger h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-times-circle fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Ditolak</h6>
                                            <h3 class="card-text mb-0">{{ $ditolak }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif ($user->level_id == 5)
                        {{-- TEKNISI DASHBOARD --}}
                        <h5 class="font-weight-bold">Halo, {{ $user->nama }}</h5>
                        <div class="row mt-4">
                            {{-- Total Tugas --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-primary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-list-alt fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Total Tugas</h6>
                                            <h3 class="card-text mb-0">{{ $total }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tugas Diproses --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-info h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-hourglass-half fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Sedang Diproses</h6>
                                            <h3 class="card-text mb-0">{{ $diproses }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tugas Dikerjakan --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-warning h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-solid fa-spinner fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Dikerjakan</h6>
                                            <h3 class="card-text mb-0">{{ $dikerjakan }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tugas Selesai --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-success h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-check-circle fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Selesai</h6>
                                            <h3 class="card-text mb-0">{{ $selesai }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif ($user->level_id == 6)
                        {{-- SARPRAS DASHBOARD --}}
                        <h5 class="font-weight-bold">Halo, {{ $user->nama }}</h5>
                        <div class="row mt-4">
                            {{-- Total Laporan --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-primary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-list-alt fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Total Laporan</h6>
                                            <h3 class="card-text mb-0">{{ $total }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Laporan Pending --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-secondary h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-clock fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Laporan masuk</h6>
                                            <h3 class="card-text mb-0">{{ $pending }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ditolak --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-danger h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-times-circle fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Ditolak</h6>
                                            <h3 class="card-text mb-0">{{ $ditolak }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Laporan Diproses --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-info h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-hourglass-half fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Diproses</h6>
                                            <h3 class="card-text mb-0">{{ $diproses }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tugas Dikerjakan --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-warning h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa--solid fa-spinner fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Dikerjakan</h6>
                                            <h3 class="card-text mb-0">{{ $dikerjakan }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Laporan Selesai --}}
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card text-white bg-success h-100 shadow">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="fa fa-check-circle fa-3x mr-3"></i>
                                        <div>
                                            <h6 class="card-title mb-1">Selesai</h6>
                                            <h3 class="card-text mb-0">{{ $selesai }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @else
                        <h5>Halo {{ $user->name }}</h5>
                        <p>Anda tidak memiliki role yang dikenali sistem.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection