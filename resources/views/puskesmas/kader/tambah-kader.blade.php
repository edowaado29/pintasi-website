@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <style>
            .colored-toast.swal2-icon-error {
                background-color: #f27474 !important;
            }
        </style>
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Kader</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Tambah Kader</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="/b/profil" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 mt-3">
                            <div class="card" style="height: 100%">
                                <div class="card-body px-0 pt-0 pb-2 mt-3">
                                    <div class="container">
                                        <form action="{{ route('b/add_kader') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="nama" class="form-label text-secondary fs-6">Nama
                                                    Kader <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                    name="nama" value="{{ old('nama') }}">
                                                @error('nama')
                                                    <script>
                                                        const ErrorNama = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label text-secondary fs-6">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}">
                                                @error('email')
                                                    <script>
                                                        const ErrorEmail = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label text-secondary fs-6">Password <span
                                                        class="text-danger">*</span></label>
                                                <input type="password"
                                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                    id="password" name="password" aria-label="Password"
                                                    value="{{ old('password') }}">
                                                <i class="fa fa-eye position-absolute" id="togglePassword"
                                                    style="cursor: pointer; right: 30px; top: 250px;"></i>
                                                @error('password')
                                                    <script>
                                                        const ErrorPassword = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="role" class="form-label text-secondary fs-6">Role<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control @error('role') is-invalid @enderror"
                                                    id="role" name="role">
                                                    <option value="kader" {{ old('role') == 'kader' ? 'selected' : '' }}>
                                                        Kader</option>
                                                    <option value="bidan" {{ old('role') == 'bidan' ? 'selected' : '' }}>
                                                        Bidan</option>
                                                </select>
                                                @error('role')
                                                    <script>
                                                        const ErrorRole = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="no_hp" class="form-label text-secondary fs-6">Nomor
                                                    Handphone</label>
                                                <input type="regex" class="form-control" id="no_hp" name="no_hp"
                                                    value="{{ old('no_hp') }}" onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInput(this)">
                                            </div>

                                            <div class="mb-3">
                                                <label for="alamat" class="form-label text-secondary fs-6">Alamat</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="foto" class="text-secondary fs-6">Foto Kader
                                                    (Maksimal 2MB)</label><br>
                                                <input type="file" class="form-control" id="foto" name="foto"
                                                    onchange="kaderPreview(event)">
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="/b/kader"
                                                        class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                        class="btn btn-sm bg-gradient-success w-100">Tambah</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 mt-3">
                            <div class="card">
                                <div class="card-body px-0 pt-0 pb-2 mt-3">
                                    <div class="container">
                                        <div class="mb-3">
                                            <div class="preview">
                                                <label class="text-secondary fs-6">Pratinjau Foto Kader</label><br>
                                                <img src="{{ asset('assets/img/no_image.png') }}" class="mb-3"
                                                    id="kaderPreview"
                                                    style="width: 100%; height: 400px; border: 2px solid #d4d4d4; border-radius: 10px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function hanyaAngka(event) {
            var angka = (event.which) ? event.which : event.keyCode
            if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
                return false;
            return true;
        }

        function cekPanjangInput(input) {
            if (input.value.length > 13) {
                input.value = input.value.slice(0, 13);
            }
        }
    </script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script>
        function kaderPreview(event) {
            const reader = new FileReader();
            const image = document.getElementById('kaderPreview');
            image.style.maxWidth = '100%';
            image.style.maxHeight = '400px';
            image.style.display = 'none';

            reader.onload = function() {
                if (reader.readyState === FileReader.DONE) {
                    image.src = reader.result;
                    image.style.display = 'block';
                }
            }

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>

    <script>
        if (typeof ErrorNama !== 'undefined' || typeof ErrorRole !== 'undefined' || typeof ErrorEmail !== 'undefined' ||
            typeof ErrorPassword !== 'undefined') {
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
            Toast.fire({
                icon: 'warning',
                title: "Form Tidak Boleh Kosong",
            });
            if (typeof ErrorNama !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNama,
                });
            } else if (typeof ErrorRole !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorRole,
                });
            } else if (typeof ErrorEmail !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorEmail,
                });
            } else if (typeof ErrorPassword !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorPassword,
                });
            }
        }
    </script>
@endsection
