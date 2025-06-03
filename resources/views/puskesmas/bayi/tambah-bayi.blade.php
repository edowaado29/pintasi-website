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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Bayi</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Tambah Data Bayi</h6>
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
                                        <form action="{{ route('b/add_bayi') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="id_ibu" class="form-label text-secondary fs-6">Ibu <span
                                                        class="text-danger">*</span></label>
                                                <select id="id_ibu"
                                                    class="form-control  @error('id_ibu') is-invalid @enderror"
                                                    name="id_ibu">
                                                    <option value="">Pilih Ibu</option>
                                                    @foreach ($ibus as $ibu)
                                                        <option value="{{ $ibu->id }}"
                                                            {{ old('id_ibu') == $ibu->id ? 'selected' : '' }}>
                                                            {{ $ibu->nama_ibu }}</option>
                                                    @endforeach
                                                </select>
                                                @error('id_ibu')
                                                    <script>
                                                        const ErrorIbu = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama_bayi" class="form-label text-secondary fs-6">Nama
                                                    Bayi <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama_bayi') is-invalid @enderror"
                                                    id="nama_bayi" name="nama_bayi" value="{{ old('nama_bayi') }}">
                                                @error('nama_bayi')
                                                    <script>
                                                        const ErrorNama = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="tanggal_lahir" class="form-label text-secondary fs-6">Tanggal
                                                    Lahir <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir"
                                                    name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                                @error('tanggal_lahir')
                                                    <script>
                                                        const ErrorTanggal = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="jenis_kelamin" class="form-label text-secondary fs-6">Jenis
                                                    Kelamin<span class="text-danger">*</span></label>
                                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                                    id="jenis_kelamin" name="jenis_kelamin">
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="Laki-laki"
                                                        {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                                @error('jenis_kelamin')
                                                    <script>
                                                        const ErrorJenis = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_kk" class="form-label text-secondary fs-6">Nomor
                                                    KK</label>
                                                <input type="regex" class="form-control" id="no_kk" name="no_kk"
                                                    value="{{ old('no_kk') }}" onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInput(this)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="nik_bayi" class="form-label text-secondary fs-6">NIK
                                                    Bayi</label>
                                                <input type="regex" class="form-control @error('nik_bayi') is-invalid @enderror" id="nik_bayi" name="nik_bayi"
                                                    value="{{ old('nik_bayi') }}" onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInput(this)">
                                                @error('nik_bayi')
                                                    <script>
                                                        const ErrorNik = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="nama_ayah" class="form-label text-secondary fs-6">Nama
                                                    Ayah</label>
                                                <input type="text" class="form-control" id="nama_ayah"
                                                    name="nama_ayah" value="{{ old('nama_ayah') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="foto_bayi" class="text-secondary fs-6">Foto Bayi
                                                    (Maksimal 2MB)</label><br>
                                                <input type="file" class="form-control" id="foto_bayi"
                                                    name="foto_bayi" onchange="bayiPreview(event)">
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="/b/bayi"
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
                                                <label class="text-secondary fs-6">Pratinjau Foto Bayi</label><br>
                                                <img src="{{ asset('assets/img/no_image.png') }}" class="mb-3"
                                                    id="bayiPreview"
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
            if (input.value.length > 16) {
                input.value = input.value.slice(0, 16);
            }
        }
    </script>

    <script>
        function bayiPreview(event) {
            const reader = new FileReader();
            const image = document.getElementById('bayiPreview');
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
        if (typeof ErrorIbu !== 'undefined' || typeof ErrorNik !== 'undefined' || typeof ErrorNama !== 'undefined' || typeof ErrorJenis !== 'undefined' ||
            typeof ErrorTanggal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'green',
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
            if (typeof ErrorIbu !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorIbu,
                });
            } else if (typeof ErrorNik !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNik,
                });
            } else if (typeof ErrorNama !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNama,
                });
            } else if (typeof ErrorJenis !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorJenis,
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
