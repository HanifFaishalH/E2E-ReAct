<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sign up - srtdash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('srtdash/assets/images/icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css"
        media="all" />
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/responsive.css') }}">
    <script src="{{ asset('srtdash/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .login-area {
            position: relative;
            background: url('{{ asset('bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-area::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .logo-jti-wrapper {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 3;
            background-color: white;
            border-radius: 50%;
            padding: 25px;
            box-shadow: 0px 0 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 140px;
        }

        .logo-jti {
            width: 120px;
            height: auto;
        }

        #loginForm {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            border-radius: 10px;
        }

        .login-form-head {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-color: #815ef6;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .form-gp {
            margin-bottom: 20px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper input,
        .input-icon-wrapper select {
            width: 100%;
            height: 40px;
            padding-right: 35px;
            padding-left: 10px;
            box-sizing: border-box;
        }

        .input-icon-wrapper i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c63ff;
            font-size: 18px;
        }

        .submit-btn-area button {
            width: 100%;
            background-color: #6c63ff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-btn-area button:hover {
            background-color: #574dcf;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <div class="logo-jti-wrapper">
        <img src="{{ asset('jti.png') }}" alt="Logo JTI" class="logo-jti">
    </div>

    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">
                <form id="loginForm" method="POST" action="{{ url('register') }}">
                    @csrf
                    <div class="login-form-head">
                        <h4>Sign up</h4>
                        <p>Hello there, Sign up and Join with Us</p>
                    </div>
                    <div class="login-form-body">

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="margin-bottom: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Level -->
                        <div class="form-gp">
                            <label for="level_id">Level Pengguna</label>
                            <div class="input-icon-wrapper">
                                <select name="level_id" id="level_id" class="form-control" required>
                                    <option value="">- Pilih Level -</option>
                                    @foreach($level->whereIn('level_id', [2, 3, 4,5]) as $l)
                                        <option value="{{ $l->level_id }}" {{ old('level_id') == $l->level_id ? 'selected' : '' }}>
                                            {{ $l->level_nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="ti-user"></i>
                            </div>
                        </div>

                        <!-- No Induk -->
                        <div class="form-gp">
                            <label for="no_induk">No Induk (NIM/NIP)</label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="no_induk" id="no_induk" placeholder="Masukkan NIM/NIP anda"
                                    value="{{ old('no_induk') }}" required>
                                <i class="ti-id-badge"></i>
                            </div>
                        </div>

                        <!-- Nama -->
                        <div class="form-gp">
                            <label for="nama">Nama Lengkap</label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap anda"
                                    value="{{ old('nama') }}" required>
                                <i class="ti-user"></i>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="form-gp">
                            <label for="username">Username</label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="username" id="username" placeholder="Masukkan username"
                                    value="{{ old('username') }}" required>
                                <i class="ti-user"></i>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-gp">
                            <label for="password">Password</label>
                            <div class="input-icon-wrapper">
                                <input type="password" name="password" id="password" placeholder="Masukkan password"
                                    required>
                                <i class="ti-lock"></i>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-gp">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <div class="input-icon-wrapper">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    placeholder="Ulangi password anda" required>
                                <i class="ti-lock"></i>
                            </div>
                        </div>

                        <div class="submit-btn-area mt-5">
                            <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>

                        <div class="form-footer text-center mt-5">
                            <p class="text-muted">Sudah punya akun? <a href="{{ url('/') }}">Sign in</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Success -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#6c63ff; color:white;">
                    <h5 class="modal-title" id="successModalLabel">Pendaftaran Berhasil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/') }}" class="btn btn-primary">Login Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('srtdash/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slicknav.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            @if(session('success'))
                $('#successModal').modal('show');
            @endif
      });
    </script>

</body>

</html>