@extends('kader.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Pemeriksaan</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Edit Data Pemeriksaan</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="/k/profil" class="nav-link text-white font-weight-bold px-0">
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
        <div class="container-fluid py-4 px-5">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>EDIT DATA PEMERIKSAAN</h6>
                </div>
                <div class="container">
                    <div class="card-body px-0 pt-0 pb-2 mt-3">
                        <div class="container">
                            <form action="{{ route('k/update_pemeriksaan', $pemeriksaan->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="text" name="id_bayi" value="{{ $pemeriksaan->bayi->id }}" disabled>
                                <input type="text" name='jk' value="{{ $pemeriksaan->bayi->jenis_kelamin }}" disabled>
                                <input type="text" name='usia_bulan' value="{{ number_format($usia_bulan, 0) }}" disabled>
                                <div class="mb-3">
                                    <label for="nama" class="form-label text-secondary fs-6">Nama <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ old('nama', $pemeriksaan->bayi->nama_bayi) }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="bb" class="form-label text-secondary fs-6">Berat Badan(kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="bb" name="bb"
                                        value="{{ old('bb', $pemeriksaan->bb) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="tb" class="form-label text-secondary fs-6">Tinggi Badan(cm) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tb" name="tb"
                                        value="{{ old('tb', $pemeriksaan->tb) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="lk" class="form-label text-secondary fs-6">Lingkar Kepala(cm)</label>
                                    <input type="text" class="form-control" id="lk" name="lk"
                                        value="{{ old('lk', $pemeriksaan->lk) }}">
                                </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <a href="{{ route('k/pemeriksaan') }}"
                                            class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-sm bg-gradient-success w-100">Edit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
