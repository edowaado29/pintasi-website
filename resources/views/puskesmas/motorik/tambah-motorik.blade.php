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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Daftar Motorik</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Tambah Daftar Motorik</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="" class="nav-link text-white font-weight-bold px-0">
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
                                        <form action="{{route ('add_motorik')}}" method="post">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="min_usia" class="form-label text-secondary fs-6">Usia Minimal (Bulan)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('min_usia') is-invalid @enderror" id="min_usia"
                                                    name="min_usia" value="{{ old('min_usia') }}" onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInput(this)">
                                                @error('min_usia')
                                                    <script>
                                                        const ErrorMinUsia = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="max_usia" class="form-label text-secondary fs-6">Usia Maksimal (Bulan)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('max_usia') is-invalid @enderror" id="max_usia"
                                                    name="max_usia" value="{{ old('max_usia') }}" onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInput(this)">
                                                @error('max_usia')
                                                    <script>
                                                        const ErrorMaxUsia = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="capaian_motorik" class="form-label text-secondary fs-6">Capaian Motorik
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('capaian_motorik') is-invalid @enderror" id="capaian_motorik"
                                                    name="capaian_motorik" value="{{ old('capaian_motorik') }}">
                                                @error('capaian_motorik')
                                                    <script>
                                                        const ErrorCapaian = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="{{ route('motorik') }}"
                                                        class="btn btn-sm bg-gradient-danger w-100" type="button">Kembali</a>
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
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function hanyaAngka(event) {
            var angka = (event.which) ? event.which : event.keyCode
            if (angka < 48 || angka > 57)
                return false;
            return true;
        }

        function cekPanjangInput(input) {
            if (input.value.length > 2) {
                input.value = input.value.slice(0, 2);
            }
        }
    </script>

    <script>
        if (typeof ErrorMinUsia !== 'undefined' || typeof ErrorMaxUsia !== 'undefined' || typeof ErrorCapaian !== 'undefined') {
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
            if (typeof ErrorMinUsia !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorMinUsia,
                });
            } else if (typeof ErrorMaxUsia !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorMaxUsia,
                });
            } else if (typeof ErrorCapaian !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorCapaian,
                });
            } 
        }
    </script>
@endsection
