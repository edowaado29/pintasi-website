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
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Daftar Bahan</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Daftar Bahan</h6>
                </nav>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>TABEL DATA DAFTAR BAHAN</h6>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 px-4">
                                    <a href="/tambah_bahan" class="btn btn-sm bg-gradient-primary">Tambah Bahan</a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input class="form-control" id="search" type="text"
                                        placeholder="Masukkan kata kunci ...">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 px-4">
                                    <form action="{{ route('import_bahan') }}" method="POST"
                                        enctype="multipart/form-data" class="d-flex align-items-center">
                                        @csrf
                                        <input type="file" name="file" class="form-control form-control-sm me-2">
                                        <button type="submit" class="btn btn-sm bg-gradient-primary">Import Bahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2 mt-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">No
                                            </th>
                                            <th
                                                class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">
                                                Nama Bahan</th>
                                            <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bahans as $bhn)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                </td>
                                                <td>
                                                    <p class="text-x font-weight-bold mb-0">{{ $bhn->nama_bahan }}
                                                </td>
                                                <td class="align-middle text-sm">
                                                    <form action="/hapus_bahan" method="POST" id="delete-form">
                                                        <a href="{{ route('detail_bahan', $bhn->id) }}"
                                                            class="btn btn-sm bg-gradient-primary">Detail</a>
                                                        <a href="{{ route('edit_bahan', $bhn->id) }}"
                                                            class="btn btn-sm bg-gradient-success">Edit</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ $bhn->id }}')">HAPUS</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center font-weight-bold">Data Bahan belum
                                                    Tersedia</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>
    <script>
        const baseUrl = "{{ url('/hapus_bahan') }}";

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
@endsection
