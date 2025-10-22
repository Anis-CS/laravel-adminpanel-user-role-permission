<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login</title>
    <meta name="description" content="Login" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admin/assets/images/favicon.ico') }}">

    <!-- External CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />


    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .h-100vh {
            height: 100vh;
        }

        .login-bg {
            background-image: url('admin/assets/images/login_admin/login_bg.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;

            border-bottom-left-radius: 150px;
            /* this adds the curved bottom-left corner */
            overflow: hidden;
            /* important for the curve to clip the image */
        }

        .brand_logo {
            max-width: 200px;
            margin-top: 20px;
            margin-right: 20px;
        }

        .form-wrapper {
            max-width: 400px;
            width: 100%;
        }

        .signin-btn {
            background-color: #007bff;
            color: white;
        }

        .signin-btn:hover {
            background-color: #0056b3;
            color: white;
        }

        .password-showHide {
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .signin-btn {
            background-color: #0e52ff;
            color: white;
            border: none;
        }

        .signin-btn:hover {
            background-color: #0b43d6;
            /* Optional: slightly darker shade for hover effect */
            color: white;
        }

        .input-highlight {
            border: 1px solid #0e52ff;
            border-radius: 8px;
            padding-left: 10px;
            transition: border 0.3s ease;
        }

        .input-highlight:focus {
            border-left: 4px solid #0e52ff;
            border-top: 1px solid #0e52ff;
            border-right: 1px solid #0e52ff;
            border-bottom: 1px solid #0e52ff;
            outline: none;
            box-shadow: none;
            /* Optional: removes Bootstrap's default glow */
        }

        .table tbody tr {
            cursor: pointer;
        }

        @media (max-width: 767.98px) {
            .login-bg {
                display: none !important;
            }

            .form-wrapper {
                padding: 20px;
            }

            .panel-footer {
                margin-top: 2rem;
            }

            .brand_logo {
                display: block;
                margin: 0 auto 1rem;
                max-width: 150px;
            }

            .h-100vh {
                height: auto;
                min-height: 100vh;
            }
        }

        @media (max-width: 991.98px) {
            .login-bg {
                border-bottom-left-radius: 0;
            }
        }

        /* Optional: More spacing for smaller screens */
        .input-py {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .fs-32 {
            font-size: 1.75rem;
            /* Mobile friendly heading size */
        }

        .fs-12 {
            font-size: 0.875rem;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row h-100vh align-items-center px-0">
            <!-- Left side - form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="form-wrapper">
                    <div class="mb-4 text-center">
                        <h2 class="fs-32 fw-bold">Sign in</h2>
                        <p>Enter your email and password to sign in!</p>
                    </div>
                    <form class="register-form mt-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="email" class="form-control input-highlight input-py" id="email"
                                name="email" placeholder="Enter email" required autocomplete="email" />
                            <span class="invalid-feedback text-start"></span>
                        </div>

                        <div class="form-input mb-3 position-relative">
                            <input class="form-control input-highlight input-py" type="password" id="password"
                                name="password" placeholder="Password" required />
                            <div class="password-showHide" onclick="togglePassword(this)">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="remember"
                                        id="remember" />
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a class="text-primary" href="{{ route('password.request') }}">Recover password</a>
                            </div>
                        </div>
                        <button type="submit" class="btn signin-btn w-100">Sign in</button>
                    </form>

                    <div class="bottom-text text-center my-3"></div>

                    {{-- <div class="panel-footer mt-5 bg-light p-3 rounded">
                        <table class="table table-bordered fs-12 mb-0">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="autoFillLogin('tauhedulislam0001@gmail.com', '12345678')">
                                    <td>tauhedulislam0001@gmail.com</td>
                                    <td>12345678</td>
                                    <td>Administrator</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>

            <!-- Right side - background image with logo -->
            <div class="col-lg-6 login-bg d-none d-lg-flex justify-content-end align-items-start py-2">
                <img class="brand_logo" src="{{ asset('admin/assets/images/login_admin/logo_white.png') }}"
                    alt="Brand Logo" />
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function autoFillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function togglePassword(el) {
            const input = el.parentElement.querySelector('input[type="password"], input[type="text"]');
            const icon = el.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('info'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: '{{ session('info') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>

</body>

</html>
