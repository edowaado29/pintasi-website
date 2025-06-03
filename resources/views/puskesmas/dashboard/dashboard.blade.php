@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
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
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="card-header pb-3" style="background: #4DA8DA;">
                            <h6 style="color: #fff;">Jumlah Kader</h6>
                        </div>
                        <div class="container position-relative">
                            <div class="card-body px-4 pt-2 pb-1">
                                <p style="font-size: 4rem; font-weight: 800;">{{ $count_kader }}</p>
                                <img src="../assets/img/illustrations/kader-icon.png" alt="bb"
                                    style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.225;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="card-header pb-3" style="background: #80D8C3;">
                            <h6 style="color: #fff;">Jumlah Akun Ibu</h6>
                        </div>
                        <div class="container position-relative">
                            <div class="card-body px-4 pt-2 pb-1">
                                <p style="font-size: 4rem; font-weight: 800;">{{ $count_ibu }}</p>
                                <img src="../assets/img/illustrations/ibu-icon.png" alt="bb"
                                    style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.125;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="card-header pb-3" style="background: #FFD66B;">
                            <h6 style="color: #fff;">Jumlah Bayi</h6>
                        </div>
                        <div class="container position-relative">
                            <div class="card-body px-4 pt-2 pb-1">
                                <p style="font-size: 4rem; font-weight: 800;">{{ $count_bayi }}</p>
                                <img src="../assets/img/illustrations/lk.png" alt="bb"
                                    style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.125;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="card-header pb-3" style="background: #81C784;">
                            <h6 style="color: #fff;">Jumlah Resep</h6>
                        </div>
                        <div class="container position-relative">
                            <div class="card-body px-4 pt-2 pb-1">
                                <p style="font-size: 4rem; font-weight: 800;">{{ $count_resep }}</p>
                                <img src="../assets/img/illustrations/food-icon.png" alt="bb"
                                    style="position: absolute; bottom: 15px; right: 20px; width: 100px; opacity: 0.15;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-7 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header pb-0 pt-3 bg-transparent">
                            <h6 class="text-capitalize">Grafik Pemeriksaan</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                <canvas id="barPeriksa" class="chart-canvas" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card card-carousel overflow-hidden h-100 p-0">
                        <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                            <div class="carousel-inner border-radius-lg h-100">
                                @foreach ($artikel as $key => $a)
                                    <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}"
                                        style="background-image: url('{{ asset('/storage/artikels/' . $a->gambar) }}'); background-size: cover;">
                                        <a href="{{ route('b/detail-artikel', $a->id) }}" style="text-decoration:none;">
                                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                                <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                            </div>
                                            <h5 class="text-white mb-1">{{ $a->judul }}</h5>
                                            <p>
                                                {{ \Illuminate\Support\Str::limit(strip_tags($a->konten), 100, '...') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev w-5 me-3" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next w-5 me-3" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if ($message = Session::get('message'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: "{{ $message }}",
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
    });
</script>
@endif

    <script>
        const ctxBar = document.getElementById('barPeriksa').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Pemeriksaan',
                    data: {!! json_encode($dataBar) !!},
                    backgroundColor: '#4285F4'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <style>
        .card-bayi {
            max-width: 220px;
            margin: 0 auto;
            border-radius: 16px;
        }

        .card-bayi .card-body {
            padding: 18px 8px !important;
        }
    </style>
@endsection
