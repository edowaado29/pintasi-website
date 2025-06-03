<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>PINTASI</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />

</head>

<body>
    <div class="container">
        <div class="form-box login">
            <form action="{{ route('loginUser') }}" method="POST" encrypt="multipart/form-data">
                @csrf
                <h1>login</h1>
                <div class="input-box">
                    <input type="text"class="form-control form-control-lg @error('email') is-invalid @enderror"
                        name="email" placeholder="Email" value="{{ old('email') }}">
                    <i class='bx bxs-user'></i>
                    @error('email')
                        <script>
                            const ErrorEmail = '{{ $message }}';
                        </script>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                        id="password" name="password" placeholder="Password" aria-label="Password"
                        value="{{ old('password') }}">
                    <i class="fa fa-eye position-absolute" id="togglePassword"></i>
                    @error('password')
                        <script>
                            const ErrorPassword = '{{ $message }}';
                        </script>
                    @enderror
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember" style="margin:0; cursor:pointer;">Remember Me</label>
                    </div>
                    <div class="forgot-link">
                        <a href="#" id="forgot-link">Forgot Password?</a>
                    </div>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
        <div class="form-box forgot">
            <form action="{{ route('forgot_password_act') }}" method="POST">
                @csrf
                <h1>Forgot Password</h1>
                <div class="input-box">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <i class='bx bxs-envelope'></i>
                </div>
                @error('email')
                    <script>
                        const ErrorEmail = '{{ $message }}';
                    </script>
                @enderror
                <button type="submit" class="btn">Send Reset Link</button>
            </form>
        </div>
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>SELAMAT DATANG DI</h1>
                <h2>APLIKASI PINTASI</h2>
                <p>Mitra Bidan untuk MPASI dan Pemantauan Bayi!</p>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>SELAMAT DATANG DI</h1>
                <h2>APLIKASI PINTASI</h2>
                <p>Mitra Bidan untuk MPASI dan Pemantauan Bayi!</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function(e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the eye icon
        this.classList.toggle('fa-eye-slash');
    });
</script>

<script>
    if (typeof ErrorEmail !== 'undefined' || typeof ErrorPassword !== 'undefined' || typeof AuthError !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'red',
            customClass: {
                popup: 'colored-toast swal2-icon-error',
            },
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        })
        if (typeof ErrorEmail !== 'undefined') {
            Toast.fire({
                icon: 'warning',
                title: ErrorEmail
            });
        } else if (typeof ErrorPassword !== 'undefined') {
            Toast.fire({
                icon: 'warning',
                title: ErrorPassword
            });
        } else if (typeof AuthError !== 'undefined') {
            Toast.fire({
                icon: 'warning',
                title: AuthError
            });
        }
    }
</script>

@if ($message = Session::get('message'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: "{{ $message }}",
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    </script>
@endif

@if ($message = Session::get('failed'))
    <script>
        Swal.fire({
            icon: 'error',
            title: "{{ $message }}",
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    </script>
@endif

@if ($message = Session::get('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'green',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        })
        Toast.fire({
            icon: 'success',
            title: "{{ $message }}"
        });
    </script>
@endif

<script>
    if (typeof ErrorEmail !== 'undefined') {
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
        if (typeof ErrorEmail !== 'undefined') {
            Toast.fire({
                icon: 'warning',
                title: ErrorEmail,
            });
        }
    }
</script>
