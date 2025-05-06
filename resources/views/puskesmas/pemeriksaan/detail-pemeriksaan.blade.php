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
          <h6 class="font-weight-bolder text-white mb-0">Detail Data Pemeriksaan</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4 px-5">
      <div class="card">
        <div class="card-header pb-0">
          <h6>DETAIL DATA PEMERIKSAAN</h6>
        </div>
        <div class="container">
          <div class="card-body px-0 pt-0 mb-3 mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6 style="font-size: .75rem; opacity: .9;">INFORMASI PEMERIKSAAN BAYI</h6>
                            </div>
                            <div class="card-body pt-0 mt-2">
                                <div class="row">
                                    <div class="col-4">Nama</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ $pemeriksaan->bayi->nama }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4">Jenis Kelamin</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ $pemeriksaan->bayi->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4">Usia</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ number_format($usia_bulan, 0) }} Bulan</div>
                                </div>
                                <div class="row">
                                    <div class="col-4">Berat Badan</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ $pemeriksaan->bb }}kg</div>
                                </div>
                                <div class="row">
                                    <div class="col-4">Tinggi Badan</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ $pemeriksaan->tb }}cm</div>
                                </div>
                                <div class="row">
                                    <div class="col-4">Tanggal Periksa</div>
                                    <div class="col-1">:</div>
                                    <div class="col-7">{{ $pemeriksaan->tgl_periksa }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6 style="font-size: .75rem; opacity: .9">INFORMASI STATUS GIZI & NUTRISI HARIAN</h6>
                            </div>
                            <div class="card-body pt-0 mt-2">
                                <div class="row">
                                    <div class="col-5">Status Gizi</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->status_gizi }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Kalori Harian</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->kalori }}kal</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Protein Harian</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->protein }}g</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Lemak Harian</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->lemak }}g</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Karbohidrat Harian</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->karbo }}g</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Serat Harian</div>
                                    <div class="col-1">:</div>
                                    <div class="col-6">{{ $pemeriksaan->serat }}g</div>
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
@endsection