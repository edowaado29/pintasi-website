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
            <div class="card">
                <div style="margin-left: 5vh;" class="py-3">
                    <a href="{{ route('b/bayi') }}">
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
                @if ($bayis->perkembangan_motorik->count())
                    <div class="row mt-3 pb-3">
                        <div class="col-12" style="padding-left: 60px;">
                            <h5 style="font-weight: 500; font-size: 1.15rem;">Perkembangan Motorik yang Dicapai:</h5>
                            <ul>
                                @foreach ($bayis->perkembangan_motorik as $pm)
                                    <li>
                                        {{ $pm->motorik->capaian_motorik ?? '-' }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
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
                <div style="display: flex; justify-content: center; margin-bottom: 24px;">
                    <div id="kategoriBtnGroup" class="btn-group-custom">
                        <button type="button" class="btn-kategori active" data-kategori="bb">BB</button>
                        <button type="button" class="btn-kategori" data-kategori="tb">TB</button>
                        <button type="button" class="btn-kategori" data-kategori="imt">IMT</button>
                    </div>
                </div>
                <canvas id="kmsChart" width="600" height="400"></canvas>
            </div>
        </div>
    </main>

    <style>
        .btn-group-custom {
            display: flex;
            background: #e0e0e0;
            border-radius: 16px;
            box-shadow: 2px 2px 8px #eee;
            overflow: hidden;
            min-width: 320px;
            align-items: stretch;
        }

        .btn-kategori {
            flex: 1;
            border: none;
            background: transparent;
            font-weight: bold;
            font-size: 1.2rem;
            color: #222;
            padding: 10px 0;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            outline: none;
        }

        .btn-kategori:not(:last-child) {
            border-right: 2px solid #222;
        }

        .btn-kategori.active {
            background: #fff;
            color: #222;
            box-shadow: 0 2px 8px #ccc;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = @json($labels);
        const dataBayi = {
            bb: @json($bb),
            tb: @json($tb),
            imt: @json($imt)
        };
        console.log(dataBayi);


        const databb_lk = {
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
        const databb_pr = {
            '-3SD': [2.0, 2.7, 3.4, 4.0, 4.4, 4.8, 5.1, 5.3, 5.6, 5.8, 5.9, 6.1, 6.3, 6.4, 6.6, 6.7, 6.9, 7.0, 7.2, 7.3,
                7.5, 7.6, 7.8, 7.9, 8.1
            ],
            '-2SD': [2.4, 3.2, 3.9, 4.5, 5.0, 5.4, 5.7, 6.0, 6.3, 6.5, 6.7, 6.9, 7.0, 7.2, 7.4, 7.6, 7.7, 7.9, 8.1, 8.2,
                8.4, 8.6, 8.7, 8.9, 9.0
            ],
            '-1SD': [2.8, 3.6, 4.5, 5.2, 5.7, 6.1, 6.5, 6.8, 7.0, 7.3, 7.5, 7.7, 7.9, 8.1, 8.3, 8.5, 8.7, 8.9, 9.1, 9.2,
                9.4, 9.6, 9.8, 10.0, 10.0
            ],
            'median': [3.2, 4.2, 5.1, 5.8, 6.4, 6.9, 7.3, 7.6, 7.9, 8.2, 8.5, 8.7, 8.9, 9.2, 9.4, 9.6, 9.8, 10.0, 10.2,
                10.4, 10.6, 10.8, 11.1, 11.3, 11.5
            ],
            '+1SD': [3.7, 4.8, 5.8, 6.6, 7.3, 7.8, 8.2, 8.6, 9.0, 9.3, 9.6, 9.9, 10.1, 10.4, 10.6, 10.9, 11.1, 11.4,
                11.6, 11.8, 12.1, 12.3, 12.5, 12.8, 13.0
            ],
            '+2SD': [4.2, 5.5, 6.6, 7.5, 8.2, 8.8, 9.3, 9.8, 10.2, 10.5, 10.9, 11.2, 11.5, 11.8, 12.1, 12.4, 12.6, 12.9,
                13.2, 13.5, 13.7, 14.0, 14.3, 14.6, 14.8
            ],
            '+3SD': [4.8, 6.2, 7.5, 8.5, 9.3, 10.0, 10.6, 11.1, 11.6, 12.0, 12.4, 12.8, 13.1, 13.5, 13.8, 14.1, 14.5,
                14.8, 15.1, 15.4, 15.7, 16.0, 16.4, 16.7, 17.0
            ],
        };

        const datatb_lk = {
            '-3SD': [44.2, 48.9, 52.4, 55.3, 57.6, 59.6, 61.2, 62.7, 64.0, 65.2, 66.4, 67.6, 68.6, 69.6, 70.6, 71.6,
                72.5, 73.3, 74.2, 75.0, 75.8, 76.5, 77.2, 78.0, 78.7
            ],
            '-2SD': [46.1, 50.8, 54.4, 57.3, 59.7, 61.7, 63.3, 64.8, 66.2, 67.5, 68.7, 69.9, 71.0, 72.1, 73.1, 74.1,
                75.0, 76.0, 76.9, 77.7, 78.6, 79.4, 80.2, 81.0, 81.7
            ],
            '-1SD': [48.0, 52.8, 56.4, 59.4, 61.8, 63.8, 65.5, 67.0, 68.4, 69.7, 71.0, 72.2, 73.4, 74.5, 75.6, 76.6,
                77.6, 78.6, 79.6, 80.5, 81.4, 82.3, 83.1, 83.9, 84.8
            ],
            'median': [49.9, 54.7, 58.4, 61.4, 63.9, 65.9, 67.6, 69.2, 70.6, 72.0, 73.3, 74.5, 75.7, 76.9, 78.0, 79.1,
                80.2, 81.2, 82.3, 83.2, 84.2, 85.1, 86.0, 86.9, 87.8
            ],
            '+1SD': [51.8, 56.7, 60.4, 63.5, 66.0, 68.0, 69.8, 71.3, 72.8, 74.2, 75.6, 76.9, 78.1, 79.3, 80.5, 81.7,
                82.8, 83.9, 85.0, 86.0, 87.0, 88.0, 89.0, 89.9, 90.9
            ],
            '+2SD': [53.7, 58.6, 62.4, 65.5, 68.0, 70.1, 71.9, 73.5, 75.0, 76.5, 77.9, 79.2, 80.5, 81.8, 83.0, 84.2,
                85.4, 86.5, 87.7, 88.8, 89.8, 90.9, 91.9, 92.9, 93.9
            ],
            '+3SD': [55.6, 60.6, 64.4, 67.6, 70.1, 72.2, 74.0, 75.7, 77.2, 78.7, 80.1, 81.5, 82.9, 84.2, 85.5, 86.7,
                88.0, 89.2, 90.4, 91.5, 92.6, 93.8, 94.9, 95.9, 97.0
            ],
        };
        const datatb_pr = {
            '-3SD': [43.6, 47.8, 51.0, 53.5, 55.6, 57.4, 58.9, 60.3, 61.7, 62.9, 64.1, 65.2, 66.3, 67.3, 68.3, 69.3,
                70.2, 71.1, 72.0, 72.8, 73.7, 74.5, 75.2, 76.0, 76.7
            ],
            '-2SD': [45.4, 49.8, 53.0, 55.6, 57.8, 59.6, 61.2, 62.7, 64.0, 65.3, 66.5, 67.7, 68.9, 70.0, 71.0, 72.0,
                73.0, 74.0, 74.9, 75.8, 76.7, 77.5, 78.4, 79.2, 80.0
            ],
            '-1SD': [47.3, 51.7, 55.0, 57.7, 59.9, 61.8, 63.5, 65.0, 66.4, 67.7, 69.0, 70.3, 71.4, 72.6, 73.7, 74.8,
                75.8, 76.8, 77.8, 78.8, 79.7, 80.6, 81.5, 82.3, 83.2
            ],
            'median': [49.1, 53.7, 57.1, 59.8, 62.1, 64.0, 65.7, 67.3, 68.7, 70.1, 71.5, 72.8, 74.0, 75.2, 76.4, 77.5,
                78.6, 79.7, 80.7, 81.7, 82.7, 83.7, 84.6, 85.5, 86.4
            ],
            '+1SD': [51.0, 55.6, 59.1, 61.9, 64.3, 66.2, 68.0, 69.6, 71.1, 72.6, 73.9, 75.3, 76.6, 77.8, 79.1, 80.2,
                81.4, 82.6, 83.6, 84.7, 85.7, 86.7, 87.7, 88.7, 89.6
            ],
            '+2SD': [52.9, 57.6, 61.1, 64.0, 66.4, 68.5, 70.3, 71.9, 73.5, 75.0, 76.4, 77.8, 79.2, 80.5, 81.7, 83.0,
                84.2, 85.4, 86.5, 87.6, 88.7, 89.8, 90.8, 91.9, 92.9
            ],
            '+3SD': [54.7, 59.5, 63.2, 66.1, 68.6, 70.7, 72.5, 74.2, 75.8, 77.4, 78.9, 80.3, 81.7, 83.1, 84.4, 85.7,
                87.0, 88.2, 89.4, 90.6, 91.7, 92.9, 94.0, 95.0, 96.1
            ],
        };

        const dataimt_lk = {
            '-3SD': [10.2, 11.3, 12.5, 13.1, 13.4, 13.5, 13.6, 13.7, 13.6, 13.6, 13.5, 13.4, 13.4, 13.3, 13.2, 13.1,
                13.1, 13.0, 12.9, 12.9, 12.8, 12.8, 12.7, 12.7, 12.7
            ],
            '-2SD': [11.1, 12.4, 13.7, 14.3, 14.5, 14.7, 14.7, 14.8, 14.7, 14.7, 14.6, 14.5, 14.4, 14.3, 14.2, 14.1,
                14.0, 13.9, 13.9, 13.8, 13.7, 13.7, 13.6, 13.6, 13.6
            ],
            '-1SD': [12.2, 13.6, 15.0, 15.5, 15.8, 15.9, 16.0, 16.0, 15.9, 15.8, 15.7, 15.6, 15.5, 15.4, 15.3, 15.2,
                15.1, 15.0, 14.9, 14.9, 14.8, 14.7, 14.7, 14.6, 14.6
            ],
            'median': [13.4, 14.9, 16.3, 16.9, 17.2, 17.3, 17.3, 17.3, 17.3, 17.2, 17.0, 16.9, 16.8, 16.7, 16.6, 16.4,
                16.3, 16.2, 16.1, 16.1, 16.0, 15.9, 15.8, 15.8, 15.7
            ],
            '+1SD': [14.8, 16.3, 17.8, 18.4, 18.7, 18.8, 18.8, 18.8, 18.7, 18.6, 18.5, 18.4, 18.2, 18.1, 18.0, 17.8,
                17.7, 17.6, 17.5, 17.4, 17.3, 17.2, 17.2, 17.1, 17.0
            ],
            '+2SD': [16.3, 17.8, 19.4, 20.0, 20.3, 20.5, 20.5, 20.5, 20.4, 20.3, 20.1, 20.0, 19.8, 19.7, 19.5, 19.4,
                19.3, 19.1, 19.0, 18.9, 18.8, 18.7, 18.7, 18.6, 18.5
            ],
            '+3SD': [18.1, 19.4, 21.1, 21.8, 22.1, 22.3, 22.3, 22.3, 22.2, 22.1, 22.0, 21.8, 21.6, 21.5, 21.3, 21.2,
                21.0, 20.9, 20.8, 20.7, 20.6, 20.5, 20.4, 20.3, 20.3
            ],
        };
        const dataimt_pr = {
            '-3SD': [10.1, 10.8, 11.8, 12.4, 12.7, 12.9, 13.0, 13.0, 13.0, 12.9, 12.9, 12.8, 12.7, 12.6, 12.6, 12.5,
                12.4, 12.4, 12.3, 12.3, 12.2, 12.2, 12.2, 12.2, 12.1
            ],
            '-2SD': [11.1, 12.0, 13.0, 13.6, 13.9, 14.1, 14.1, 14.2, 14.1, 14.1, 14.0, 13.9, 13.8, 13.7, 13.6, 13.5,
                13.5, 13.4, 13.3, 13.2, 13.2, 13.1, 13.1, 13.1, 13.1
            ],
            '-1SD': [12.2, 13.2, 14.3, 14.9, 15.2, 15.4, 15.5, 15.5, 15.4, 15.3, 15.2, 15.1, 15.0, 14.9, 14.8, 14.7,
                14.6, 14.5, 14.4, 14.4, 14.3, 14.2, 14.2, 14.2, 14.2
            ],
            'median': [13.3, 14.4, 15.8, 16.4, 16.7, 16.8, 16.9, 16.9, 16.8, 16.7, 16.6, 16.5, 16.4, 16.2, 16.1, 16.0,
                15.9, 15.8, 15.7, 15.7, 15.6, 15.5, 15.5, 15.4, 15.4
            ],
            '+1SD': [14.6, 16.0, 17.3, 17.9, 18.3, 18.4, 18.5, 18.5, 18.4, 18.3, 18.2, 18.0, 17.9, 17.7, 17.6, 17.5,
                17.3, 17.2, 17.1, 17.1, 17.0, 16.9, 16.9, 16.9, 16.8
            ],
            '+2SD': [16.1, 17.5, 19.0, 19.7, 20.0, 20.2, 20.3, 20.3, 20.2, 20.1, 19.9, 19.8, 19.6, 19.5, 19.3, 19.2,
                18.9, 18.9, 18.8, 18.8, 18.7, 18.6, 18.6, 18.5, 18.4
            ],
            '+3SD': [17.7, 19.1, 20.7, 21.5, 22.0, 22.2, 22.3, 22.3, 22.2, 22.1, 21.9, 21.8, 21.6, 21.5, 21.3, 21.1,
                20.9, 20.8, 20.7, 20.7, 20.5, 20.5, 20.4, 20.4, 20.3
            ],
        };

        const jenisKelamin = "{{ $bayis->jenis_kelamin }}";

        function getDataKMS(kategori) {
            if (kategori === 'bb') return jenisKelamin === 'Laki-laki' ? databb_lk : databb_pr;
            if (kategori === 'tb') return jenisKelamin === 'Laki-laki' ? datatb_lk : datatb_pr;
            if (kategori === 'imt') return jenisKelamin === 'Laki-laki' ? dataimt_lk : dataimt_pr;
        }

        let kategori = 'bb';
        let chart;

        function renderChart() {
            const dataKMS = getDataKMS(kategori);
            const dataBayiKategori = dataBayi[kategori];

            const judul = kategori === 'bb' ? 'Grafik KMS - Berat Badan Berdasarkan Umur (bulan)' :
                kategori === 'tb' ? 'Grafik KMS - Tinggi Badan Berdasarkan Umur (bulan)' :
                'Grafik KMS - IMT Berdasarkan Umur (bulan)';
            const satuanY = kategori === 'bb' ? 'Berat Badan (kg)' :
                kategori === 'tb' ? 'Tinggi Badan (cm)' :
                'IMT';
            const maxY = kategori === 'bb' ? 18 : kategori === 'tb' ? 100 : 25;

            if (chart) chart.destroy();

            chart = new Chart(document.getElementById('kmsChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: '-3 SD',
                            data: dataKMS['-3SD'],
                            borderColor: 'red',
                            borderWidth: 1,
                            fill: false
                        },
                        {
                            label: '-2 SD',
                            data: dataKMS['-2SD'],
                            borderColor: 'orange',
                            borderWidth: 1,
                            fill: {
                                target: 0,
                                above: 'rgba(255, 0, 0, 0.1)'
                            } // antara -3 dan -2 SD
                        },
                        {
                            label: '-1 SD',
                            data: dataKMS['-1SD'],
                            borderColor: 'yellow',
                            borderWidth: 1,
                            fill: {
                                target: 1,
                                above: 'rgba(255, 165, 0, 0.1)'
                            } // antara -2 dan -1 SD
                        },
                        {
                            label: 'Normal',
                            data: dataKMS['median'],
                            borderColor: 'green',
                            borderWidth: 1,
                            fill: {
                                target: 2,
                                above: 'rgba(0, 255, 0, 0.1)'
                            } // antara -1 dan median
                        },
                        {
                            label: '+1 SD',
                            data: dataKMS['+1SD'],
                            borderColor: 'yellow',
                            borderWidth: 1,
                            fill: {
                                target: 3,
                                above: 'rgba(0, 255, 0, 0.1)'
                            } // antara median dan +1
                        },
                        {
                            label: '+2 SD',
                            data: dataKMS['+2SD'],
                            borderColor: 'orange',
                            borderWidth: 1,
                            fill: {
                                target: 4,
                                above: 'rgba(255, 255, 0, 0.1)'
                            } // antara +1 dan +2
                        },
                        {
                            label: '+3 SD',
                            data: dataKMS['+3SD'],
                            borderColor: 'red',
                            borderWidth: 1,
                            fill: {
                                target: 5,
                                above: 'rgba(255, 0, 0, 0.1)'
                            }
                        },
                        {
                            label: kategori === 'bb' ? 'Berat Badan Bayi' : kategori === 'tb' ?
                                'Tinggi Badan Bayi' : 'IMT Bayi',
                            data: dataBayiKategori,
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
                            text: judul,
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
                                text: satuanY
                            },
                            min: 0,
                            max: maxY,
                            ticks: {
                                stepSize: kategori === 'tb' ? 5 : 1
                            }
                        }
                    }
                }
            });
        }
        document.querySelectorAll('.btn-kategori').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-kategori').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                kategori = this.getAttribute('data-kategori');
                renderChart();
            });
        });

        renderChart();
    </script>
@endsection
