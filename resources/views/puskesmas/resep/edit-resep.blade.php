@extends('puskesmas.layouts.template')

@section('content')
    <main class="main-content position-relative border-radius-lg ">
        <style>
            .colored-toast.swal2-icon-error {
                background-color: #f27474 !important;
            }
        </style>
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Resep MPASI</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Tambah Data Resep MPASI</h6>
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
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 mt-3">
                            <div class="card" style="height: 100%">
                                <div class="card-body px-0 pt-0 pb-2 mt-3">
                                    <div class="container">
                                        <form action="{{ route('update_resep', $reseps->id) }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="nama_resep" class="form-label text-secondary fs-6">Nama
                                                    Resep <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama_resep') is-invalid @enderror"
                                                    id="nama_resep" name="nama_resep"
                                                    value="{{ old('nama_resep', $reseps->nama_resep) }}">
                                                @error('nama_resep')
                                                    <script>
                                                        const ErrorNama = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="total_kalori" class="form-label text-secondary fs-6">Total
                                                    Kalori<span class="text-danger">*</span></label>
                                                <input type="regex"
                                                    class="form-control @error('total_kalori') is-invalid @enderror"
                                                    id="total_kalori" name="total_kalori"
                                                    value="{{ old('total_kalori', $reseps->total_kalori) }}"
                                                    onkeypress="return hanyaAngka(event)" oninput="cekPanjangInputt(this)">
                                                @error('total_kalori')
                                                    <script>
                                                        const ErrorKalori = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="total_protein" class="form-label text-secondary fs-6">Total
                                                    Protein<span class="text-danger">*</span></label>
                                                <input type="regex"
                                                    class="form-control @error('total_protein') is-invalid @enderror"
                                                    id="total_protein" name="total_protein"
                                                    value="{{ old('total_protein', $reseps->total_protein) }}"
                                                    onkeypress="return hanyaAngka(event)" oninput="cekPanjangInputt(this)">
                                                @error('total_protein')
                                                    <script>
                                                        const ErrorProtein = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="total_lemak" class="form-label text-secondary fs-6">Total
                                                    Lemak<span class="text-danger">*</span></label>
                                                <input type="regex"
                                                    class="form-control @error('total_lemak') is-invalid @enderror"
                                                    id="total_lemak" name="total_lemak"
                                                    value="{{ old('total_lemak', $reseps->total_lemak) }}"
                                                    onkeypress="return hanyaAngka(event)" oninput="cekPanjangInputt(this)">
                                                @error('total_lemak')
                                                    <script>
                                                        const ErrorLemak = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <label for="nama_bahan" class="form-label text-secondary fs-6 mb-0">
                                                        Bahan Resep <span class="text-danger">*</span>
                                                    </label>
                                                    <i class="fas fa-solid fa-plus" onclick="addField()"
                                                        style="border: 3px solid green; color: green; padding: 3px; border-radius: 5px; cursor: pointer;"></i>
                                                </div>
                                                <div id="input_fields_wrap" class="d-flex flex-column">
                                                    @foreach ($bahans as $bhn)
                                                    <div class="form-group d-flex align-items-center mb-2">
                                                            <input type="text" name="nama_bahan[]"
                                                                class="form-control me-2" placeholder="Nama Bahan"
                                                                value="{{ old('nama_bahan', $bhn->nama_bahan) }}" required>
                                                            <input type="number" name="berat[]" class="form-control me-2"
                                                                placeholder="Berat"
                                                                value="{{ old('berat', $bhn->berat) }}" required>
                                                            <select class="form-control me-2" name="satuan_berat[]"
                                                                required>
                                                                <option value="" disabled
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == '' ? 'selected' : '' }}>
                                                                    Satuan
                                                                    Berat</option>
                                                                <option value="Gram"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Gram' ? 'selected' : '' }}>
                                                                    Gram
                                                                    (g)
                                                                </option>
                                                                <option value="Kilogram"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Kilogram' ? 'selected' : '' }}>
                                                                    Kilogram (kg)</option>
                                                                <option value="Miligram"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Miligram' ? 'selected' : '' }}>
                                                                    Miligram (mg)</option>
                                                                <option value="Ons"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Ons' ? 'selected' : '' }}>
                                                                    Ons</option>
                                                                <option value="Sendok makan (sdm)"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Sendok makan (sdm)' ? 'selected' : '' }}>
                                                                    Sendok makan (sdm)</option>
                                                                <option value="Sendok teh (sdt)"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Sendok teh (sdt)' ? 'selected' : '' }}>
                                                                    Sendok teh (sdt)</option>
                                                                <option value="Cup"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Cup' ? 'selected' : '' }}>
                                                                    Cup
                                                                    (cangkir)</option>
                                                                <option value="Liter"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Liter' ? 'selected' : '' }}>
                                                                    Liter (l)</option>
                                                                <option value="Mililiter"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Mililiter' ? 'selected' : '' }}>
                                                                    Mililiter (ml)</option>
                                                                <option value="Buah"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Buah' ? 'selected' : '' }}>
                                                                    Buah
                                                                </option>
                                                                <option value="Lembar"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Lembar' ? 'selected' : '' }}>
                                                                    Lembar</option>
                                                                <option value="Batang"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Batang' ? 'selected' : '' }}>
                                                                    Batang</option>
                                                                <option value="Siung"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Siung' ? 'selected' : '' }}>
                                                                    Siung</option>
                                                                <option value="Potong"
                                                                    {{ old('satuan_berat', $bhn->satuan_berat) == 'Potong' ? 'selected' : '' }}>
                                                                    Potong</option>
                                                            </select>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="removeField(this)">-</button>
                                                            </div>
                                                            @endforeach
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="langkah"
                                                    class="form-label text-secondary fs-6">Langkah-langkah<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" id="langkah" name="langkah" rows="3">{{ old('langkah', $reseps->langkah) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jumlah_porsi" class="form-label text-secondary fs-6">Jumlah
                                                    Porsi<span class="text-danger">*</span></label>
                                                <input type="regex" class="form-control" id="jumlah_porsi"
                                                    name="jumlah_porsi"
                                                    value="{{ old('jumlah_porsi', $reseps->jumlah_porsi) }}"
                                                    onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInputt(this)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="min_usia" class="form-label text-secondary fs-6">Minimal
                                                    Usia (bulan)<span class="text-danger">*</span></label>
                                                <input type="regex" class="form-control" id="min_usia"
                                                    name="min_usia" value="{{ old('min_usia', $reseps->min_usia) }}"
                                                    onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInputt(this)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="max_usia" class="form-label text-secondary fs-6">Maksimal Usia (bulan)<span
                                                        class="text-danger">*</span></label>
                                                <input type="regex" class="form-control" id="max_usia"
                                                    name="max_usia" value="{{ old('max_usia', $reseps->max_usia) }}"
                                                    onkeypress="return hanyaAngka(event)"
                                                    oninput="cekPanjangInputt(this)">
                                            </div>
                                            <div class="form-group">
                                                <label for="gambar_resep" class="text-secondary fs-6">GambarResep
                                                    (Maksimal 2MB)
                                                </label><br>
                                                <input type="file" class="form-control" id="gambar_resep"
                                                    name="gambar_resep" onchange="resepPreview(event)">
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="/resep"
                                                        class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                        class="btn btn-sm bg-gradient-success w-100">Edit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 mt-3">
                            <div class="card">
                                <div class="card-body px-0 pt-0 pb-2 mt-3">
                                    <div class="container">
                                        <div class="mb-3">
                                            <div class="preview">
                                                <label class="text-secondary fs-6">Pratinjau Gambar Resep</label><br>
                                                @if ($reseps->gambar_resep && file_exists(public_path('storage/reseps/' . $reseps->gambar_resep)))
                                                    <img src="{{ asset('storage/reseps/' . $reseps->gambar_resep) }}"
                                                        class="mb-3" id="resepPreview"
                                                        style="width: 100%; height: 400px; border: 2px solid #d4d4d4; border-radius: 10px;">
                                                @else
                                                    <img src="{{ asset('assets/img/no_image.png') }}" class="mb-3"
                                                        id="resepPreview"
                                                        style="width: 100%; height: 400px; border: 2px solid #d4d4d4; border-radius: 10px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function hanyaAngka(event) {
            var angka = (event.which) ? event.which : event.keyCode
            if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
                return false;
            return true;
        }

        function cekPanjangInput(input) {
            if (input.value.length > 16) {
                input.value = input.value.slice(0, 16);
            }
        }

        function cekPanjangInputt(input) {
            if (input.value.length > 3) {
                input.value = input.value.slice(0, 3);
            }
        }
    </script>
    <script>
        function addField() {
            var wrapper = document.getElementById("input_fields_wrap");
            var newField = document.createElement("div");
            newField.className = "form-group d-flex align-items-center mb-2";
            newField.innerHTML =
                `<input type="text" name="nama_bahan[]" class="form-control me-2" placeholder="Nama Bahan" required>
                <input type="number" name="berat[]" class="form-control me-2" placeholder="Berat" min="1" required>
                <select class="form-control me-2" name="satuan_berat[]" required>
        <option value="" disabled selected>Satuan Berat</option>
        <option value="Gram">Gram (g)</option>
        <option value="Kilogram">Kilogram (kg)</option>
        <option value="Miligram">Miligram (mg)</option>
        <option value="Ons">Ons</option>
        <option value="Sendok makan (sdm)">Sendok makan (sdm)</option>
        <option value="Sendok teh (sdt)">Sendok teh (sdt)</option>
        <option value="Cup">Cup (cangkir)</option>
        <option value="Liter">Liter (l)</option>
        <option value="Mililiter">Mililiter (ml)</option>
        <option value="Buah">Buah</option>
        <option value="Lembar">Lembar</option>
        <option value="Batang">Batang</option>
        <option value="Siung">Siung</option>
        <option value="Potong">Potong</option>
    </select>
    <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)">-</button>`;
            wrapper.appendChild(newField);
        }

        function removeField(button) {
            button.parentNode.remove();
        }
    </script>
    <script>
        function resepPreview(event) {
            const reader = new FileReader();
            const image = document.getElementById('resepPreview');
            image.style.maxWidth = '100%';
            image.style.maxHeight = '400px';
            image.style.display = 'none';

            reader.onload = function() {
                if (reader.readyState === FileReader.DONE) {
                    image.src = reader.result;
                    image.style.display = 'block';
                }
            }

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>

    <script>
        if (typeof ErrorNama !== 'undefined' || typeof ErrorLangkah !== 'undefined' || typeof ErrorPorsi !== 'undefined' ||
            typeof ErrorMin !== 'undefined' || typeof ErrorMax !== 'undefined' || typeof ErrorKalori !== 'undefined' ||
            typeof ErrorProtein !== 'undefined' || typeof ErrorLemak !== 'undefined' || typeof ErrorGambar !== 'undefined'
        ) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'green',
                customClass: {
                    popup: 'colored-toast swal2-icon-error',
                },
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            })
            Toast.fire({
                icon: 'warning',
                title: "Form Tidak Boleh Kosong",
            });
            if (typeof ErrorEmail !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorEmail,
                });
            } else if (typeof ErrorPassword !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorPassword,
                });
            } else if (typeof ErrorNik !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNik,
                });
            } else if (typeof ErrorNama !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNama,
                });
            }
        }
    </script>
@endsection
