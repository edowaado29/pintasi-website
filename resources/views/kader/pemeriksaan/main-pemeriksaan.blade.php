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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Data Pemeriksaan</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Data Pemeriksaan</h6>
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
                                    @forelse($pemeriksaans as $pemeriksaan)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                            </td>
                                            <td>
                                                <p class="text-x font-weight-bold mb-0">{{ $pemeriksaan->bayi->nama_bayi ?? "-" }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-x font-weight-bold mb-0 text-center">{{ $pemeriksaan->bb }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-x font-weight-bold mb-0 text-center">{{ $pemeriksaan->tb }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-x font-weight-bold mb-0 text-center">
                                                    {{ $pemeriksaan->tgl_periksa }}</p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <form action="" method="post" id="delete-form">
                                                    <a href="{{ route('k/detail_pemeriksaan', $pemeriksaan->id) }}"
                                                        class="btn btn-sm bg-gradient-primary">Detail</a>
                                                    <a href="{{ route('k/edit_pemeriksaan', $pemeriksaan->id) }}"
                                                        class="btn btn-sm bg-gradient-success">Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm bg-gradient-danger"
                                                        onclick="confirmDelete('{{ $pemeriksaan->id }}')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center font-weight-bold">Data pemeriksaan belum
                                                Tersedia.</td>
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
        <form action="" id="formPilihBayi">
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
                                <select class="form-control" id="id_bayi" name="id_bayi">
                                    <option value="">-- Pilih Bayi --</option>
                                    @foreach ($bayis as $bayi)
                                        <option value="{{ $bayi->id }}">{{ $bayi->nama_bayi  }}</option>
                                    @endforeach
                                </select>
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

    <script>
        document.getElementById('formPilihBayi').addEventListener('submit', function(e) {
            e.preventDefault();
            let idBayi = document.getElementById('id_bayi').value;

            if (idBayi) {
                window.location.href = "{{ url('/k/tambah_pemeriksaan') }}/" + idBayi;
            } else {
                alert('Silakan pilih bayi terlebih dahulu.');
            }
        });
    </script>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<script>
    const baseUrl = "{{ url('k/delete_pemeriksaan') }}";

    function confirmDelete(id) {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Ingin Menghapus Data Ini",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = document.getElementById("delete-form");
                deleteForm.action = `${baseUrl}/${id}`;
                deleteForm.submit();
            }
        });
    }
</script>

@if (session('message'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'green',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        })
        Toast.fire({
            icon: 'success',
            title: "{{ session('message') }}"
        });
    </script>
@endif
