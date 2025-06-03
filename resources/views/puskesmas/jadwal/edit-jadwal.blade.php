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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Jadwal Posyandu</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Edit Jadwal</h6>
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
                                        <form action="{{ route('b/update_jadwal', $jadwal->id) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="jenis_pemeriksaan" class="form-label text-secondary fs-6">Jenis
                                                    Pemeriksaan<span class="text-danger">*</span></label>
                                                <select
                                                    class="form-control @error('jenis_pemeriksaan') is-invalid @enderror"
                                                    id="jenis_pemeriksaan" name="jenis_pemeriksaan">
                                                    <option value="Imunisasi"
                                                        {{ old('jenis_pemeriksaan', $jadwal->jenis_pemeriksaan) == 'Imunisasi' ? 'selected' : '' }}>
                                                        Imunisasi</option>
                                                    <option value="Posyandu"
                                                        {{ old('jenis_pemeriksaan', $jadwal->jenis_pemeriksaan) == 'Posyandu' ? 'selected' : '' }}>
                                                        Posyandu</option>
                                                </select>
                                                @error('jenis_pemeriksaan')
                                                    <script>
                                                        const ErrorPeriksa = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_pemeriksaan"
                                                    class="form-label text-secondary fs-6">Tanggal Pemeriksaan<span
                                                        class="text-danger">*</span></label>
                                                <input type="date"
                                                    class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
                                                    id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                                                    value="{{ old('tanggal_pemeriksaan', $jadwal->tanggal_pemeriksaan) }}">
                                                @error('tanggal_pemeriksaan')
                                                    <script>
                                                        const ErrorTanggal = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="jam_pemeriksaan" class="form-label text-secondary fs-6">Jam
                                                    Pemeriksaan<span class="text-danger">*</span></label>
                                                <input type="time"
                                                    class="form-control @error('jam_pemeriksaan') is-invalid @enderror"
                                                    id="jam_pemeriksaan" name="jam_pemeriksaan"
                                                    value="{{ old('jam_pemeriksaan', $jadwal->jam_pemeriksaan) }}">
                                                @error('jam_pemeriksaan')
                                                    <script>
                                                        const ErrorJam = '{{ $message }}';
                                                    </script>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="tempat" class="form-label text-secondary fs-6">Tempat
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('tempat') is-invalid @enderror"
                                                        id="tempat" name="tempat"
                                                        value="{{ old('tempat', $jadwal->tempat) }}">
                                                    @error('tempat')
                                                        <script>
                                                            const ErrorTempat = '{{ $message }}';
                                                        </script>
                                                    @enderror
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-6">
                                                        <a href="/b/jadwal" class="btn btn-sm bg-gradient-danger w-100"
                                                            type="button">Kembali</a>
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
            if (typeof ErrorPeriksa !== 'undefined' || typeof ErrorTanggal !== 'undefined' || typeof ErrorJam !== 'undefined' ||
                typeof ErrorTempat !== 'undefined') {
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
                if (typeof ErrorPeriksa !== 'undefined') {
                    Toast.fire({
                        icon: 'warning',
                        title: ErrorPeriksa,
                    });
                } else if (typeof ErrorTanggal !== 'undefined') {
                    Toast.fire({
                        icon: 'warning',
                        title: ErrorTanggal,
                    });
                } else if (typeof ErrorJam !== 'undefined') {
                    Toast.fire({
                        icon: 'warning',
                        title: ErrorJam,
                    });
                } else if (typeof ErrorTempat !== 'undefined') {
                    Toast.fire({
                        icon: 'warning',
                        title: ErrorTempat,
                    });
                }
            }
        </script>
    @endsection
