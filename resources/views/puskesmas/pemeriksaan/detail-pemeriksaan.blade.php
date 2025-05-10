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
            <div class="card-body">
                <div class="row text-center justify-content-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex align-items-center p-3 rounded shadow-sm">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background-color: #f2f2f2;">
                                <img src="../assets/img/illustrations/name.png" alt="name" style="width: 30px;">
                            </div>
                            <div class="text-start">
                                <small class="text-muted" style="font-size: 1rem; font-weight: 600;">Nama</small><br>
                                <p class="mb-0" style="font-size: 1.25rem; font-weight: 800;">{{ $pemeriksaan->bayi->nama_bayi }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex align-items-center p-3 rounded shadow-sm">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background-color: #f2f2f2;">
                                <img src="../assets/img/illustrations/gender.png" alt="gender" style="width: 30px;">
                            </div>
                            <div class="text-start">
                                <small class="text-muted" style="font-size: 1rem; font-weight: 600;">Jenis Kelamin</small><br>
                                <p class="mb-0" style="font-size: 1.25rem; font-weight: 800;">{{ $pemeriksaan->bayi->jenis_kelamin }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex align-items-center p-3 rounded shadow-sm">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background-color: #f2f2f2;">
                                <img src="../assets/img/illustrations/age.png" alt="age" style="width: 30px;">
                            </div>
                            <div class="text-start">
                                <small class="text-muted" style="font-size: 1rem; font-weight: 600;">Usia</small><br>
                                <p class="mb-0" style="font-size: 1.25rem; font-weight: 800;">{{ number_format($usia_bulan, 0) }} Bulan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-1 px-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header pb-3" style="background: #577ED8;">
                        <h6 style="color: #fff;">Berat Badan</h6>
                    </div>
                    <div class="container position-relative">
                        <div class="card-body px-4 pt-2 pb-1">
                            <p style="font-size: 4rem; font-weight: 800;">{{ $pemeriksaan->bb }}<span style="font-size: 2rem;">kg</span></p>
                            <img src="../assets/img/illustrations/bb.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.225;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header pb-3" style="background: #6C5CEB;">
                        <h6 style="color: #fff;">Tinggi Badan</h6>
                    </div>
                    <div class="container position-relative">
                        <div class="card-body px-4 pt-2 pb-1">
                            <p style="font-size: 4rem; font-weight: 800;">{{ $pemeriksaan->tb }}<span style="font-size: 2rem;">cm</span></p>
                            <img src="../assets/img/illustrations/tb.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.125;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header pb-3" style="background: #CF654B;">
                        <h6 style="color: #fff;">Lingkar kepala</h6>
                    </div>
                    <div class="container position-relative">
                        <div class="card-body px-4 pt-2 pb-1">
                            <p style="font-size: 4rem; font-weight: 800;">{{ $pemeriksaan->lk }}<span style="font-size: 2rem;">cm</span></p>
                            <img src="../assets/img/illustrations/lk.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.125;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header pb-3" style="background: #68AD5A;">
                        <h6 style="color: #fff;">Indeks Massa Tubuh</h6>
                    </div>
                    <div class="container position-relative">
                        <div class="card-body px-4 pt-2 pb-1">
                            <p style="font-size: 4rem; font-weight: 800;">{{ number_format($pemeriksaan->imt, 2) }}</p>
                            <img src="../assets/img/illustrations/imt.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.15;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4 px-5">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card rounded shadow-sm">
                            <div class="card-header pb-0">
                                <h6>Kebutuhan Kalori Harian</h6>
                            </div>
                            <div class="container positiion-relative">
                                <div class="card-body">
                                    <h6 style="font-size: 2.75rem; font-weight: 800;">{{ $pemeriksaan->kalori }}<span style="font-size: 1.75rem;">kal</span></h6>
                                    <img src="../assets/img/illustrations/calorie.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.2;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="card rounded shadow-sm">
                            <div class="card-header pb-0">
                                <h6>Kebutuhan Protein Harian</h6>
                            </div>
                            <div class="container positiion-relative">
                                <div class="card-body">
                                    <h6 style="font-size: 2.75rem; font-weight: 800;">{{ $pemeriksaan->protein }}<span style="font-size: 1.75rem;">g</span></h6>
                                    <img src="../assets/img/illustrations/protein.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.2;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 col-sm=12">
                        <div class="card rounded shadow-sm">
                            <div class="card-header pb-0">
                                <h6>Kebutuhan Lemak Harian</h6>
                            </div>
                            <div class="container positiion-relative">
                                <div class="card-body">
                                    <h6 style="font-size: 2.75rem; font-weight: 800;">{{ $pemeriksaan->lemak }}<span style="font-size: 1.75rem;">g</span></h6>
                                    <img src="../assets/img/illustrations/fat.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.2;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm=12">
                        <div class="card rounded shadow-sm">
                            <div class="card-header pb-0">
                                <h6>Kebutuhan Karbohidrat Harian</h6>
                            </div>
                            <div class="container positiion-relative">
                                <div class="card-body">
                                    <h6 style="font-size: 2.75rem; font-weight: 800;">{{ $pemeriksaan->karbo }}<span style="font-size: 1.75rem;">g</span></h6>
                                    <img src="../assets/img/illustrations/carbs.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.2;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm=12">
                        <div class="card rounded shadow-sm">
                            <div class="card-header pb-0">
                                <h6>Kebutuhan Serat Harian</h6>
                            </div>
                            <div class="container positiion-relative">
                                <div class="card-body">
                                    <h6 style="font-size: 2.75rem; font-weight: 800;">{{ $pemeriksaan->serat }}<span style="font-size: 1.75rem;">g</span></h6>
                                    <img src="../assets/img/illustrations/fiber.png" alt="bb" style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.2;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header pb-3" style="background: #1E439C;">
                        <h6 style="color: #fff">Status Gizi</h6>
                    </div>
                    <div class="card-body pt-4 pb-1 d-flex align-items-center">
                        <div class="me-4 mb-4" style="
                            width: 30px;
                            height: 30px;
                            border-radius: 50%;
                            background-color: #68AD5A;
                            opacity: 0.75;
                        "></div>
                        <p class="pb-3" style="font-size: 2.5rem; font-weight: 800;">{{ $pemeriksaan->status_gizi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 text-center">
                                    <h6 style="color: #4C80D1; font-size: 4rem;">1</h6>
                                </div>
                                <div class="col-10 border rounded">
                                    <div class="mx-3 mt-3">
                                        <h6>Pengisian Data Pemeriksaan</h6>
                                        <p>Langkah pertama adalah pengisian data oleh bidan atau kader. Data yang harus dimasukan meliputi Nama Bayi (otomatis mendapatkan jenis kelamin dan usia bayi), Berat Badan (BB) dalam satuan kilogram, Tinggi Badan (TB) dalam satuan centimeter, dan Lingkar Kepala (LK) dalam satuan centimeter. Anda memasukan nama  <b>{{ $pemeriksaan->bayi->nama }} (Otomatis mendapatkan jenis kelamin {{ $pemeriksaan->bayi->jk == 'L' ? 'Laki-laki' : 'Perempuan' }} dan usia {{ number_format($usia_bulan, 0) }} bulan), BB {{ $pemeriksaan->bb }}kg, TB {{ $pemeriksaan->tb }}cm, LK {{ $pemeriksaan->lk }}cm.</b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-10 border rounded">
                                    <div class="mx-3 mt-3">
                                        <h6>Perhitungan Indeks Massa Tubuh (IMT)</h6>
                                        <p>Setelah data diinput, sistem secara otomatis menghitung Indeks Massa Tubuh (IMT). Rumus yang digunakan adalah :</p>
                                        <p>IMT = BB (kg) / (TB (m)) ^ 2</p>
                                        <p>Dari data yang anda inputkan, didapatkan hasil sebagai berikut :</p>
                                        <p><b>IMT = {{ $pemeriksaan->bb }} / {{ $pemeriksaan->tb / 100 }} ^ 2</b></p>
                                        <p><b>IMT = {{ number_format($pemeriksaan->imt, 2) }}</b></p>
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <h6 style="color: #4C80D1; font-size: 4rem;">2</h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2 text-center">
                                    <h6 style="color: #4C80D1; font-size: 4rem;">3</h6>
                                </div>
                                <div class="col-10 border rounded">
                                    <div class="mx-3 mt-3">
                                        <h6>Penentuan Status Gizi</h6>
                                        <p>Sistem akan mencocokkan nilai IMT, usia, dan jenis kelamin bayi dengan tabel status gizi, untuk mengetahui apakah bayi masuk kategori: Gizi Buruk, Gizi Kurang, Gizi Baik, Berpotensi Gizi Lebih, Gizi Lebih, Obesitas.</p>
                                        <p>Berdasarkan data standar, <b>IMT {{ number_format($pemeriksaan->imt, 2) }}</b> pada bayi <b>{{ $pemeriksaan->bayi->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</b> usia <b>{{ number_format($usia_bulan, 0) }} bulan</b> termasuk dalam kategori <b>{{ $pemeriksaan->status_gizi }}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-10 border rounded">
                                    <div class="mx-3 mt-3">
                                        <h6>Penentuan Kebutuhan Nutrisi Harian</h6>
                                        <p>Setelah status gizi diketahui, sistem akan menentukan asupan nutrisi harian berdasarkan usia bayi. Nutrisi meliputi: Kalori, Protein, Lemak, Karbohidrat, Serat.</p>
                                        <p>Berdasarkan data standar, usia <b>{{ number_format($usia_bulan, 0) }} bulan</b> membutuhkan nutrisi harian sebesar kalori <b>{{ $pemeriksaan->kalori }}kal</b>, protein <b>{{ $pemeriksaan->protein }}g</b>, lemak <b>{{ $pemeriksaan->lemak }}g</b>, karbohidrat <b>{{ $pemeriksaan->karbo }}g</b>, dan serat <b>{{ $pemeriksaan->serat }}g</b>.</p>
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <h6 style="color: #4C80D1; font-size: 4rem;">4</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        myModal.show();
    });
</script>
@endsection