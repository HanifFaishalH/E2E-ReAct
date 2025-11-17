<!-- area menu sidebar dimulai -->
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="logo" class="img-fluid" style="max-height: 100px;">
            </a>
        </div>
    </div>

    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <!-- Dashboard (accessible to all) -->
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'home' ? 'active' : '' }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @if (Auth::check() && Auth::user()->getRole() === 'admin')
                        <!-- Kelola (admin only) -->
                        <li
                            class="nav-item has-submenu {{ in_array($activeMenu, ['level', 'kelola-periode', 'user', 'kelola-prioritas']) ? 'mm-active' : '' }}">
                            <a href="javascript:void(0)" class="nav-link section-title">
                                <i class="fa fa-gear"></i>
                                <span>Kelola</span>
                            </a>
                            <ul
                                class="nav nav-second-level collapse {{ in_array($activeMenu, ['level', 'kelola-periode', 'user', 'kelola-prioritas']) ? 'in' : '' }}">
                                <li>
                                    <a href="{{ url('/level') }}"
                                        class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                                        <i class="fa fa-list"></i> Level
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/laporan/periode') }}"
                                        class="nav-link {{ $activeMenu == 'kelola-periode' ? 'active' : '' }}">
                                        <i class="fa fa-calendar"></i> Kelola Periode
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/user') }}"
                                        class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                                        <i class="fa fa-users"></i> Kelola Pengguna
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/bobot') }}"
                                        class="nav-link {{ $activeMenu == 'bobot' ? 'active' : '' }}">
                                        <i class="fa fa-users"></i> Pembobotan
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Sarana Prasarana (admin only) -->
                        <li
                            class="nav-item has-submenu {{ in_array($activeMenu, ['sarana', 'gedung', 'barang', 'lantai', 'ruang']) ? 'mm-active' : '' }}">
                            <a href="javascript:void(0)" class="nav-link section-title">
                                <i class="fa fa-building"></i>
                                <span>Sarana Prasarana</span>
                            </a>
                            <ul
                                class="nav nav-second-level collapse {{ in_array($activeMenu, ['sarana', 'gedung', 'barang', 'lantai', 'ruang']) ? 'in' : '' }}">
                                <li><a href="{{ url('/sarana') }}"
                                        class="nav-link {{ $activeMenu == 'sarana' ? 'active' : '' }}"><i
                                            class="fa fa-building"></i> Kelola Sarana</a></li>
                                <li><a href="{{ url('/gedung') }}"
                                        class="nav-link {{ $activeMenu == 'gedung' ? 'active' : '' }}"><i
                                            class="fa fa-university"></i> Kelola Gedung</a></li>
                                <li><a href="{{ url('/barang') }}"
                                        class="nav-link {{ $activeMenu == 'barang' ? 'active' : '' }}"><i
                                            class="fa fa-cube"></i> Kelola Barang</a></li>
                                <li>
                                    <a href="{{ url('/lantai') }}"
                                        class="nav-link {{ $activeMenu == 'lantai' ? 'active' : '' }}">
                                        <i class="fa fa-building"></i> Kelola Lantai
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/ruang') }}"
                                        class="nav-link {{ $activeMenu == 'ruang' ? 'active' : '' }}">
                                        <i class="fa fa-home"></i> Kelola Ruang
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (Auth::check() && in_array(Auth::user()->getRole(), ['mhs', 'dosen', 'tendik']))
                        <!-- Laporan (for regular users) -->
                        <li class="nav-item">
                            <a href="{{ url('/laporan') }}"
                                class="nav-link {{ $activeMenu == 'laporan' ? 'active' : '' }}">
                                <i class="fa fa-file-text"></i>
                                <span>Buat Laporan</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::check() && in_array(Auth::user()->getRole(), ['sarpras', 'teknisi']))
                        <!-- Laporan Management (for sarpras and teknisi) -->
                        <li class="nav-item">
                            <a href="{{ url('/laporan/kelola') }}"
                                class="nav-link {{ $activeMenu == 'kelola' ? 'active' : '' }}">
                                <i class="fa fa-wrench"></i>
                                <span>Kelola Laporan</span>
                            </a>
                        </li>
                    @endif

                    <!-- Daftar Umpan Balik (accessible to all) -->
                    <li class="nav-item">
                        <a href="{{ route('feedback.list') }}"
                           class="nav-link {{ $activeMenu == 'daftar-umpan-balik' ? 'active' : '' }}">
                            <i class="fa fa-list"></i>
                            <span>Daftar Umpan Balik</span>
                        </a>
                    </li>

                    <!-- Umpan Balik (only for mhs, dosen, tendik) -->
                    @if (Auth::check() && in_array(Auth::user()->getRole(), ['mhs', 'dosen', 'tendik']))
                        <li class="nav-item">
                            <a href="{{ route('feedback.index') }}"
                               class="nav-link {{ $activeMenu == 'berikan-umpan-balik' ? 'active' : '' }}">
                                <i class="fa fa-comment"></i>
                                <span>Umpan Balik</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::check() && in_array(Auth::user()->getRole(), ['sarpras']))
                        <!-- Riwayat Perbaikan (sarpras only) -->
                        <li class="nav-item">
                            <a href="{{ url('/laporan/riwayat') }}"
                               class="nav-link {{ $activeMenu == 'riwayat' ? 'active' : '' }}">
                                <i class="fa fa-wrench"></i>
                                <span>Riwayat Perbaikan</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- area menu sidebar selesai -->

<!-- Modal for Access Denied -->
<div class="modal fade" id="accessDeniedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Akses Ditolak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            </div>
        </div>
    </div>
</div>

<!-- STYLE -->
<style>
    .sidebar-menu {
        position: fixed;
        width: 250px;
        height: 100%;
        background: #2f4050;
        color: #a7b1c2;
    }

    .nav-link {
        color: #a7b1c2;
        padding: 12px 20px;
        display: block;
        transition: all 0.3s ease;
    }

    .nav-link:hover,
    .nav-link:focus {
        color: #fff;
        background: #293846;
        text-decoration: none;
    }

    .nav-link.active {
        color: #fff;
        background: #293846;
        border-left: 4px solid #19aa8d;
    }

    .nav-second-level {
        background: #293846;
        padding-left: 0;
    }

    .nav-second-level>li>a {
        padding: 10px 20px 10px 40px;
    }
</style>

<!-- SCRIPT -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
    $(document).ready(function() {
        $('#menu').metisMenu();

        // Intercept sidebar link clicks
        $('.sidebar-menu a.nav-link').on('click', function(e) {
            const href = $(this).attr('href');

            // Skip if it's a javascript link or current page
            if (!href || href === 'javascript:void(0)' || href === window.location.pathname) {
                return true;
            }

            e.preventDefault();

            // Check access using AJAX
            $.ajax({
                url: href,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    // Allow navigation if successful
                    window.location.href = href;
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        $('#accessDeniedModal').modal('show');
                    } else {
                        window.location.href = href;
                    }
                }
            });
        });
    });
</script>