@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a>
                        </li>
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
                            <button type="button" class="btn btn-sm bg-gradient-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">Tambah Data</button>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 col-sm-12 px-4">
                    </div> -->
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 mt-3">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">No
                                        </th>
                                        <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama</th>
                                        <th
                                            class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">
                                            Berat Badan(kg)</th>
                                        <th
                                            class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">
                                            Tinggi Badan(cm)</th>
                                        <th
                                            class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">
                                            Tanggal Periksa</th>
                                        <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pemeriksaans as $pmrksn)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $pmrksn->bayi->nama }}
                                            </td>
                                            <td>
                                                {{ $pmrksn->berat_badan }}
                                                {{-- <p class="text-x font-weight-bold mb-0 text-center">12</p> --}}
                                            </td>
                                            <td>
                                                {{ $pmrksn->tinggi_badan }}
                                                {{-- <p class="text-x font-weight-bold mb-0 text-center">12</p> --}}
                                            </td>
                                            <td>
                                                {{ $pmrksn->tanggal_periksa }}
                                                {{-- <p class="text-x font-weight-bold mb-0 text-center">12</p> --}}
                                            </td>
                                            <td class="align-middle text-sm">
                                                <form action="" method="POST" id="delete-form">
                                                    <a href="{{ route('detail_pemeriksaan', $pmrksn->id) }}"
                                                        class="btn btn-sm bg-gradient-primary">Detail</a>
                                                    <a href="{{ route('edit_pemeriksaan', $pmrksn->id) }}"
                                                        class="btn btn-sm bg-gradient-success">Edit</a>
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ $pmrksn->id }}')">HAPUS</button>
                                                    {{-- <a href="" class="btn btn-sm bg-gradient-danger">Hapus</a> --}}
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Data Pemeriksaan belum Tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        {{-- <form action="{{ route('tambah_pemeriksaan') }}" method="GET"> --}}
        <form action="/tambah_pemeriksaan" method="GET" id="bayiForm">
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Masukan Nama Bayi</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="my-1">
                                <input type="text" id="nama_bayi" class="form-control" placeholder="Ketik nama bayi..."
                                    autocomplete="off">
                                <input type="hidden" name="bayi_id" id="bayi_id">
                                {{-- <input type="text" class="form-control" id="nama"> --}}
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(function() {
        $('#nama_bayi').autocomplete({
            source: '/search-bayi',
            minLength: 2,
            select: function(event, ui) {
                $('#nama_bayi').val(ui.item.label);
                $('#bayi_id').val(ui.item.id);
                $("#bayiForm").attr('action', '/tambah_pemeriksaan?bayi_id=' + ui.item.id);
            }
        });
    });
</script>
