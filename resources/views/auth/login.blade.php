<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - srtdash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('srtdash/assets/images/icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/slicknav.min.css') }}">
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

        /* Jangan biarkan overlay ini blok klik */
        .login-area::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
            pointer-events: none; /* penting supaya klik diteruskan ke bawah */
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
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 140px;
            overflow: visible;
        }

        .logo-jti {
            width: 120px;
            height: auto;
            display: block;
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
        }

        .form-gp {
            margin-bottom: 20px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper input {
            width: 100%;
            padding-right: 35px;
            padding-left: 10px;
            height: 40px;
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
            background-color: #815ef6;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn-area button:hover {
            background-color: #6a4de1;
        }

        .form-footer {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="logo-jti-wrapper">
        <img src="{{ asset('jti.png') }}" alt="Logo JTI" class="logo-jti">
    </div>

    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="login-area">
        <div class="container">
            <div class="login-box">
                <form id="loginForm" method="POST" action="{{ url('login') }}">
                    @csrf
                    <div class="login-form-head">
                        <h4>Login</h4>
                        <p>Silakan login untuk melanjutkan ke website kami</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-gp">
                            <label for="username">Username</label>
                            <div class="input-icon-wrapper">
                                <input type="text" name="username" id="username" required placeholder="Username">
                                <i class="ti-email"></i>
                            </div>
                            <div class="text-danger" id="usernameError"></div>
                        </div>

                        <div class="form-gp">
                            <label for="password">Password</label>
                            <div class="input-icon-wrapper">
                                <input type="password" name="password" id="password" required placeholder="Password">
                                <i class="ti-lock"></i>
                            </div>
                            <div class="text-danger" id="passwordError"></div>
                        </div>
                        <div class="submit-btn-area">
                            <button type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="form-footer text-center mt-5">
                            <p class="text-muted">Don't have an account? <a href="{{ url('/register') }}">Sign up</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="{{ asset('srtdash/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/scripts.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AJAX Login -->
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                console.log('Submit form triggered');

                let formData = {
                    username: $('#username').val(),
                    password: $('#password').val(),
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: "{{ url('login') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil',
                                text: 'Selamat datang!',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal',
                                text: response.message || 'Username atau password salah'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat login, coba lagi.'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
