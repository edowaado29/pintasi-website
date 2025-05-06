@extends('puskesmas.layouts.template')

@section('content')
<main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Motorik Bayi</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Data Motorik Bayi</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4 px-5">
      <div class="card">
        <div class="card-header pb-0">
          <h6>TABEL DATA MOTORIK BAYI</h6>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 px-4">
              <a href="" class="btn btn-sm bg-gradient-primary">Tambah Data</a>
            </div>
            <!-- <div class="col-lg-6 col-md-6 col-sm-12 px-4">
            </div> -->
          </div>
          <div class="card-body px-0 pt-0 pb-2 mt-3">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">Usia (Bulan)</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">Motorik</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">1</h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0">2</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0">Salto</p>
                    </td>
                    <td class="align-middle text-sm">
                      <a href="" class="btn btn-sm bg-gradient-primary">Detail</a>
                      <a href="" class="btn btn-sm bg-gradient-success">Edit</a>
                      <a href="" class="btn btn-sm bg-gradient-danger">Hapus</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection