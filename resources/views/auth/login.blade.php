<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | SIAKAD SMKN 1 Curugbitung</title>

    <meta name="description" content="Sistem Informasi Akademik SMK Negeri 1 Curugbitung" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-smkn1curugbitung.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            background: url('{{ asset('assets/img/bg-smkn1curugbitung.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            z-index: 0;
        }

        .authentication-wrapper.authentication-basic {
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-height: auto !important;
            width: 100%;
            z-index: 1;
        }

        .authentication-inner {
            max-width: 400px;
            position: relative;
        }

        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, 0.97);
        }

        .logo-school {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .login-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #566a7f;
        }

        .login-subtitle {
            font-size: 0.875rem;
            color: #697a8d;
        }

        .btn-login {
            background: linear-gradient(135deg, #696cff 0%, #5f61e6 100%);
            border: none;
            padding: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #5f61e6 0%, #5254cc 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.4);
        }

        .footer-text {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            z-index: 1;
        }

        @media (max-height: 700px) {

            html,
            body {
                overflow: auto;
            }

            .footer-text {
                position: relative;
                bottom: 0;
                padding: 20px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Login Card -->
                <div class="card login-card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/img/logo-smkn1curugbitung.png') }}"
                                alt="Logo SMKN 1 Curugbitung" class="logo-school" />
                            <h4 class="login-title mb-1">SIAKAD</h4>
                            <p class="login-subtitle mb-0">Sistem Informasi Akademik</p>
                            <p class="login-subtitle">SMK Negeri 1 Curugbitung</p>
                        </div>
                        <!-- /Logo -->

                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email Anda" autofocus required />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember"> Ingat Saya </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary btn-login d-grid w-100" type="submit">
                                    <i class="bx bx-log-in-circle me-1"></i> Log In
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Login Card -->
            </div>
        </div>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} SMKN 1 Curugbitung. All rights reserved.
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
