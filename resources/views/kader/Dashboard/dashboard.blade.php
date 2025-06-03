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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
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
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
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
                <div class="col-lg-6 col-md-6 col-sm-12">
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
                    <div class="card overflow-hidden h-100 p-0">
                        <div class="card h-100 p-3">
                            <div id="jadwal-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('jadwal-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 400,
                events: [
                    @foreach ($jadwals as $jadwal)
                        {
                            title: '{{ $jadwal->jenis_pemeriksaan }} - {{ $jadwal->tempat }}',
                            start: '{{ $jadwal->tanggal_pemeriksaan }}',
                            description: 'Jam: {{ $jadwal->jam_pemeriksaan }}'
                        },
                    @endforeach
                ],
                eventDidMount: function(info) {
                    if (info.event.extendedProps.description) {
                        info.el.title = info.event.extendedProps.description;
                    }
                }
            });
            calendar.render();
        });
    </script>

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
