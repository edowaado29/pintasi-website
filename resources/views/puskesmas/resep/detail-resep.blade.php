@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <style>
            .colored-toast.swal2-icon-success {
                background-color: #a5dc86 !important;
            }
        </style>
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Resep</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Detail Resep</h6>
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
            <div class="card">
                <div style="margin-left: 5vh;" class="py-3">
                    <a href="/resep">
                        <img src="{{ asset('assets/img/back-button.png') }}" style="width: 5vh; opacity: 50%;">
                    </a>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="row">
                    <div class="col-lg-3 col-sm-12 px-5 pb-3">
                        <img src="{{ $reseps->gambar_resep !== null ? asset('/storage/Reseps/' . $reseps->gambar_resep) : asset('/assets/img/no_image.png') }}"
                            style="width: 100%; border: 2px solid #d4d4d4; border-radius: 10px;">
                    </div>
                    <div class="col-lg-9 col-sm-12">
                        <div style="margin-top: 5vh;">
                            <h2 style="font-size: 3rem;">{{ $reseps->nama_resep }}</h2>
                            <h4 style="color: #73A578; font-weight: 400;">Untuk Usia : {{ $reseps->min_usia }} -
                                {{ $reseps->max_usia }} Bulan</h4>
                        </div>
                    </div>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="row mt-3 pb-3">
                    <div class="col-2" style="padding-left: 60px;">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Total Kalori</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Total Protein</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Total Lemak</h5>
                    </div>
                    <div class="col-1">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                    </div>
                    <div class="col-2">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->total_kalori }}</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->total_protein }}</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->total_lemak }}</h5>
                    </div>
                    <div class="col-3" style="padding-left: 60px;">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Total Karbohidrat</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Total Serat</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Jumlah Porsi Makan</h5>
                    </div>
                    <div class="col-1">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                    </div>
                    <div class="col-3">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->total_karbohidrat }} Gram</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->total_serat }} Gram</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $reseps->jumlah_porsi }} Kali</h5>
                    </div>
                </div>
                <div class="row mt-3 pb-3">
                    <div class="col-2" style="padding-left: 60px;">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Bahan - Bahan</h5>
                    </div>
                    <div class="col-1">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                    </div>
                    <div class="col-2">
                        @foreach ($bahans as $bahan)
                            <h5 style="font-weight: 500; font-size: 1.15rem;">
                                {{ $bahan->daftarBahan->nama_bahan ?? '-' }} ({{ $bahan->berat }} Gram)
                            </h5>
                        @endforeach
                    </div>
                    <div class="col-3" style="padding-left: 60px;">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Langkah - Langkah</h5>
                    </div>
                    <div class="col-1">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                    </div>
                    <div class="col-2">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{!! nl2br(e($reseps->langkah)) !!}</h5>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
