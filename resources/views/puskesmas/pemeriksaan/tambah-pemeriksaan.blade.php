@extends('puskesmas.layouts.template')

@section('content')
<main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Pemeriksaan</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Tambah Data Pemeriksaan</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4 px-5">
      <div class="card">
        <div class="card-header pb-0">
          <h6>TAMBAH DATA PEMERIKSAAN</h6>
        </div>
        <div class="container">
          <div class="card-body px-0 pt-0 pb-2 mt-3">
            <div class="container">
                <form action="{{ route('store_pemeriksaan') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_bayi" value="{{ $bayi->id }}">
                    <input type="hidden" name='jk' value="{{ $bayi->jk }}">
                    <input type="hidden" name='usia_bulan' value="{{ number_format($usia_bulan, 0) }}">
                    <div class="mb-3">
                      <label for="nama" class="form-label text-secondary fs-6">Nama <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nama" value="{{ $bayi->nama }}" disabled>
                    </div>
                    <div class="mb-3">
                      <label for="bb" class="form-label text-secondary fs-6">Berat Badan(kg) <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="bb" name="bb">
                    </div>
                    <div class="mb-3">
                      <label for="tb" class="form-label text-secondary fs-6">Tinggi Badan(cm) <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="tb" name="tb">
                    </div>
                    <div class="mb-3">
                      <label for="lk" class="form-label text-secondary fs-6">Lingkar Kepala(cm)</label>
                      <input type="text" class="form-control" id="lk" name="lk">
                    </div>
                    <div class="row mt-4">
                      <div class="col-6">
                        <a href="{{ route('pemeriksaan') }}" class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                      </div>
                      <div class="col-6">
                        <button type="submit" class="btn btn-sm bg-gradient-primary w-100">Tambah</button>
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