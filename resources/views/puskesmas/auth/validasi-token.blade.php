<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo pintasi.png') }}">
    <title>
        PINTASI
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <style>
        .colored-toast.swal2-icon-success {
            background-color: #3498db !important;
        }

        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }

        .bg-nav {
            /* background-image: url('{{ asset('assets/img/bg-nav.png') }}'); */
            background-position-y: 50%;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="position-fixed w-100 min-height-300 top-0 bg-nav">
        <span class="mask bg-primary opacity-7"></span>
    </div>
    <div class="login-box d-flex justify-content-center align-items-center">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary w-35 p-5"
            style="margin-top: 25vh; box-shadow: 0px 19px 50px 0px rgba(0,0,0,0.1);">
            <div class="card-header text-center">
                <h1 class="h1"><b>Reset</b> Password</h1>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masukkan Password Baru</p>

                <form action="{{ route('validasi_forgot_password_act') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="input-group my-4">
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukkan Password Baru">
                    </div>
                    @error('password')
                        <script>
                            const ErrorPassword = '{{ $message }}';
                        </script>
                    @enderror
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn bg-gradient-primary btn-block w-100">Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script>

    <script>
        if (typeof ErrorPassword !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
                customClass: {
                    popup: 'colored-toast swal2-icon-error',
                },
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            })
            if (typeof ErrorPassword !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorPassword,
                });
            }
        }
    </script>
</body>

</html>
