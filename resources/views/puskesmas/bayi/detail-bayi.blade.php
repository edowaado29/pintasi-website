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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Bayi</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Detail Bayi</h6>
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
                    <a href="{{ route('bayi') }}">
                        <img src="{{ asset('assets/img/back-button.png') }}" style="width: 5vh; opacity: 50%;">
                    </a>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="row">
                    <div class="col-lg-3 col-sm-12 px-5 pb-3">
                        <img src="{{ $bayis->foto_bayi !== null ? asset('/storage/bayis/' . $bayis->foto_bayi) : asset('/assets/img/no_image.png') }}"
                            style="width: 100%; border: 2px solid #d4d4d4; border-radius: 10px;">
                    </div>
                    <div class="col-lg-9 col-sm-12">
                        <div style="margin-top: 5vh;">
                            <h2 style="font-size: 1.25rem; font-weight: 400; color: grey;">{{ $bayis->nik_bayi }}</h2>
                            <h3 style="font-size: 2.25rem;">{{ $bayis->nama_bayi }}</h3>
                            <h4 style="color: #73A578; font-weight: 400;">{{ $bayis->jenis_kelamin }}</h4>
                        </div>
                    </div>
                </div>
                <hr class="horizontal dark mt-0">
                <div class="row mt-3 pb-3">
                    <div class="col-3" style="padding-left: 60px;">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">No KK</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Tanggal Lahir</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Nama Ayah</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">Nama Ibu</h5>
                    </div>
                    <div class="col-1">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">:</h5>
                    </div>
                    <div class="col-7">
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $bayis->no_kk ?? '-' }}</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">
                            {{ \Carbon\Carbon::parse($bayis->tanggal_lahir)->format('d-m-Y') ?? '-' }}</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $bayis->nama_ayah ?? '-' }}</h5>
                        <h5 style="font-weight: 500; font-size: 1.15rem;">{{ $bayis->ibu->nama_ibu ?? '-' }}</h5>
                    </div>
                </div>
                @if ($bayis->pemeriksaan)
                    <div class="row mt-3 pb-3">
                        <div class="col-4" style="padding-left: 100px;">
                            <h5 style="font-weight: 500; font-size: 1.15rem;">BB : {{ $bayis->pemeriksaan->bb }} kg
                            </h5>
                        </div>
                        <div class="col-4">
                            <h5 style="font-weight: 500; font-size: 1.15rem;">TB : {{ $bayis->pemeriksaan->tb }} cm
                            </h5>
                        </div>
                        <div class="col-4">
                            <h5 style="font-weight: 500; font-size: 1.15rem;">
                                IMT : {{ number_format($bayis->pemeriksaan->imt, 2) }}
                            </h5>
                        </div>
                    </div>
                @endif
                <canvas id="kmsChart" width="600" height="400"></canvas>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const umurBulan = @json($labels);
        const beratBadan = @json($bb);

        
        const dataWHO = {
            '-3SD': [2.1, 2.9, 3.8, 4.4, 4.9, 5.3, 5.7, 5.9, 6.2, 6.4, 6.6, 6.8, 6.9, 7.1, 7.2, 7.4, 7.5, 7.7, 7.8, 8.0,
                8.1, 8.2, 8.4, 8.5, 8.6
            ],
            '-2SD': [2.5, 3.4, 4.3, 5.0, 5.6, 6.0, 6.4, 6.7, 6.9, 7.1, 7.4, 7.6, 7.7, 7.9, 8.1, 8.3, 8.4, 8.6, 8.8, 8.9,
                9.1, 9.2, 9.4, 9.5, 9.7
            ],
            '-1SD': [2.9, 3.9, 4.9, 5.7, 6.2, 6.7, 7.1, 7.4, 7.7, 8.0, 8.2, 8.4, 8.6, 8.8, 9.0, 9.2, 9.4, 9.6, 9.8,
                10.0, 10.1, 10.3, 10.5, 10.7, 10.8
            ],
            'median': [3.3, 4.5, 5.6, 6.4, 7.0, 7.5, 7.9, 8.3, 8.6, 8.9, 9.2, 9.4, 9.6, 9.9, 10.1, 10.3, 10.5, 10.7,
                10.9, 11.1, 11.3, 11.5, 11.8, 12.0, 12.2
            ],
            '+1SD': [3.9, 5.1, 6.3, 7.2, 7.8, 8.4, 8.8, 9.2, 9.6, 9.9, 10.2, 10.5, 10.8, 11.0, 11.3, 11.5, 11.7, 12.0,
                12.2, 12.5, 12.7, 12.9, 13.2, 13.4, 13.6
            ],
            '+2SD': [4.4, 5.8, 7.1, 8.0, 8.7, 9.3, 9.8, 10.3, 10.7, 11.0, 11.4, 11.7, 12.0, 12.3, 12.6, 12.8, 13.1,
                13.4, 13.7, 13.9, 14.2, 14.5, 14.7, 15.0, 15.3
            ],
            '+3SD': [5.0, 6.6, 8.0, 9.0, 9.7, 10.4, 10.9, 11.4, 11.9, 12.3, 12.7, 13.0, 13.3, 13.7, 14.0, 14.3, 14.6,
                14.9, 15.3, 15.6, 15.9, 16.2, 16.5, 16.8, 17.1
            ],
        };

        const labels = Array.from({
            length: 25
        }, (_, i) => i); 

        const ctx = document.getElementById('kmsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: '-3 SD',
                        data: dataWHO['-3SD'],
                        borderColor: 'red',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: '-2 SD',
                        data: dataWHO['-2SD'],
                        borderColor: 'orange',
                        borderWidth: 1,
                        fill: {
                            target: 0,
                            above: 'rgba(255, 0, 0, 0.1)'
                        } // antara -3 dan -2 SD
                    },
                    {
                        label: '-1 SD',
                        data: dataWHO['-1SD'],
                        borderColor: 'yellow',
                        borderWidth: 1,
                        fill: {
                            target: 1,
                            above: 'rgba(255, 165, 0, 0.1)'
                        } // antara -2 dan -1 SD
                    },
                    {
                        label: 'Normal',
                        data: dataWHO['median'],
                        borderColor: 'green',
                        borderWidth: 1,
                        fill: {
                            target: 2,
                            above: 'rgba(0, 255, 0, 0.1)'
                        } // antara -1 dan median
                    },
                    {
                        label: '+1 SD',
                        data: dataWHO['+1SD'],
                        borderColor: 'yellow',
                        borderWidth: 1,
                        fill: {
                            target: 3,
                            above: 'rgba(0, 255, 0, 0.1)'
                        } // antara median dan +1
                    },
                    {
                        label: '+2 SD',
                        data: dataWHO['+2SD'],
                        borderColor: 'orange',
                        borderWidth: 1,
                        fill: {
                            target: 4,
                            above: 'rgba(255, 255, 0, 0.1)'
                        } // antara +1 dan +2
                    },
                    {
                        label: '+3 SD',
                        data: dataWHO['+3SD'],
                        borderColor: 'red',
                        borderWidth: 1,
                        fill: {
                            target: 5,
                            above: 'rgba(255, 0, 0, 0.1)'
                        }
                    },
                    {
                        label: 'Berat Badan Bayi',
                        data: beratBadan,
                        borderColor: 'blue',
                        backgroundColor: 'blue',
                        tension: 0.2,
                        fill: false,
                        borderWidth: 2,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik KMS - Berat Badan Berdasarkan Umur (bulan)',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Umur (bulan)'
                        },
                        min: 0,
                        max: 24,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Berat Badan (kg)'
                        },
                        min: 0,
                        max: 18,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
