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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Daftar Bahan</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Tambah Daftar Bahan</h6>
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
                                        <form action="{{ route('update_bahan', $bahans->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="nama_bahan" class="form-label text-secondary fs-6">Nama
                                                    Bahan <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama_bahan') is-invalid @enderror"
                                                    id="nama_bahan" name="nama_bahan" value="{{ old('nama_bahan', $bahans->nama_bahan) }}">
                                                @error('nama_bahan')
                                                    <script>
                                                        const ErrorNama = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="kalori" class="form-label text-secondary fs-6">Kalori (gram)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('kalori') is-invalid @enderror"
                                                    id="kalori" name="kalori" value="{{ old('kalori', $bahans->kalori) }}"  onkeypress="return hanyaAngka(event)">
                                                @error('kalori')
                                                    <script>
                                                        const ErrorKalori = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="protein" class="form-label text-secondary fs-6">Protein (gram)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('protein') is-invalid @enderror"
                                                    id="protein" name="protein" value="{{ old('protein', $bahans->protein) }}"  onkeypress="return hanyaAngka(event)">
                                                @error('protein')
                                                    <script>
                                                        const ErrorProtein = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="lemak" class="form-label text-secondary fs-6">Lemak (gram)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('lemak') is-invalid @enderror"
                                                    id="lemak" name="lemak" value="{{ old('lemak', $bahans->lemak) }}"  onkeypress="return hanyaAngka(event)">
                                                @error('lemak')
                                                    <script>
                                                        const ErrorLemak = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="karbohidrat" class="form-label text-secondary fs-6">Karbohidrat (gram)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('karbohidrat') is-invalid @enderror"
                                                    id="karbohidrat" name="karbohidrat" value="{{ old('karbohidrat', $bahans->karbohidrat) }}"  onkeypress="return hanyaAngka(event)">
                                                @error('karbohidrat')
                                                    <script>
                                                        const ErrorKarbohidrat = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="serat" class="form-label text-secondary fs-6">Serat (gram)
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('serat') is-invalid @enderror"
                                                    id="serat" name="serat" value="{{ old('serat', $bahans->serat) }}"  onkeypress="return hanyaAngka(event)">
                                                @error('serat')
                                                    <script>
                                                        const ErrorSerat = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="/daftar_bahan"
                                                        class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                        class="btn btn-sm bg-gradient-success w-100">Edit</button>
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
            if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
                return false;
            return true;
        }
    </script>

    <script>
        if (typeof ErrorNama !== 'undefined' || typeof ErrorKalori !== 'undefined' || typeof ErrorProtein !== 'undefined' ||
            typeof ErrorLemak !== 'undefined'|| typeof ErrorKarbohidrat !== 'undefined' || typeof ErrorSerat !== 'undefined') {
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
            } else if (typeof ErrorKalori !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorKalori,
                });
            } else if (typeof ErrorProtein !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorProtein,
                });
            } else if (typeof ErrorLemak !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorLemak,
                });
            } else if (typeof ErrorKarbohidrat !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorKarbohidrat,
                });
            } else if (typeof ErrorSerat !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorSerat,
                });
            }
        }
    </script>
@endsection
