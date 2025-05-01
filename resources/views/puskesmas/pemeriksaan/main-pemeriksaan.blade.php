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
          <h6 class="font-weight-bolder text-white mb-0">Data Pemeriksaan</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4 px-5">
      <div class="card">
        <div class="card-header pb-0">
          <h6>TABEL DATA PEMERIKSAAN</h6>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 px-4">
              <button type="button" class="btn btn-sm bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah Data</button>
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
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Berat Badan(kg)</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Tinggi Badan(cm)</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Tanggal Periksa</th>
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
                      <p class="text-x font-weight-bold mb-0">Bobi</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">12</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">95</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">01-05-2025</p>
                    </td>
                    <td class="align-middle text-sm">
                      <a href="/detail_pemeriksaan" class="btn btn-sm bg-gradient-primary">Detail</a>
                      <a href="/edit_pemeriksaan" class="btn btn-sm bg-gradient-success">Edit</a>
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

    <!-- Modal -->
    <form action="/tambah_pemeriksaan">
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Masukan Nama Bayi</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="my-1">
                <input type="text" class="form-control" id="nama">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn bg-gradient-danger" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn bg-gradient-primary">Periksa</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </main>

@endsection