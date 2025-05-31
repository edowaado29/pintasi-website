@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <style>
            .colored-toast.swal2-icon-success {
                background-color: #a5dc86 !important;
            }

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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Profile</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Profile</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="/profil" class="nav-link text-white font-weight-bold px-0">
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

        <div class="card shadow-lg mx-4" style="margin-top: 160px;">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="{{ asset('/storage/users/' . $user->foto) }}"
                                class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $user->nama }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm" style="color: #73A578;">
                                Online
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                        id="triggerFileInput">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span class="ms-2">Ubah Foto Profil</span>
                                    </a>
                                </li>
                                <form action="{{ route('uploadImg') }}" method="post" enctype="multipart/form-data"
                                    id="fileUploadForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" id="foto" name="foto" class="d-none">
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('updateProfile') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Email</label>
                                            <input class="form-control" type="text" name="email"
                                                value="{{ old('email', $user->email) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Nomor
                                                Handphone</label>
                                            <input class="form-control" type="text" name="no_hp"
                                                value="{{ old('no_hp', $user->no_hp) }}"
                                                onkeypress="return hanyaAngka(event)" oninput="cekPanjangInput(this)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Alamat</label>
                                            <input class="form-control" type="text" name="alamat"
                                                value="{{ old('alamat', $user->alamat) }}">
                                        </div>
                                    </div>
                                </div>
                                <hr class="horizontal dark">
                                <div style="text-align: end;">
                                    <button type="submit" class="btn btn-sm bg-gradient-success w-20 mt-3">Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-profile">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Pengaturan</p>
                            </div>
                        </div>
                        <a class="nav-link mb-0 px-0 pt-3 active d-flex align-items-center mx-4" style="cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="ni ni-settings-gear-65"></i>
                            <span class="ms-2">Ubah Password</span>
                        </a>
                        <hr class="horizontal dark">
                        <a onclick="confirmLogout(event)"
                            class="nav-link mb-0 px-0 pb-3 active d-flex align-items-center mx-4"
                            style="cursor: pointer;">
                            <i class="ni ni-settings-gear-65"></i>
                            <span class="ms-2">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade align-items-center" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Password Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="updatePassword" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            @method('PUT')
                            <input class="form-control" type="password" name="password">
                            @error('password')
                                <script>
                                    const ErrorPassword = '{{ $message }}';
                                </script>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-danger" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmLogout(event) {
            Swal.fire({
                title: "Apakah Anda Yakin",
                text: "Ingin Logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/logout";
                }
            });
        }
    </script>
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
        $(document).ready(function() {
            $('#triggerFileInput').click(function(e) {
                e.preventDefault();
                $('#foto').click();
            });

            $('#foot').change(function() {
                var formData = new FormData($('#fileUploadForm')[0]);

                $.ajax({
                    url: $('#fileUploadForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                });
            });
        });
    </script>
    <script>
        // Check if ErrorImage, ErrorJudul, or ErrorDeskripsi variable exists and display toast message if any of them does
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
            Toast.fire({
                icon: 'warning',
                title: "Form Tidak Boleh Kosong",
            });
            if (typeof ErrorPassword !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorPassword,
                });
            }
        }
    </script>
    @if (session('message'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
                customClass: {
                    popup: 'colored-toast',
                },
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            })
            Toast.fire({
                icon: 'success',
                title: "{{ session('message') }}"
            });
        </script>
    @endif
@endsection
