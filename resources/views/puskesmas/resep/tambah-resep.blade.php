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
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12 mt-3">
                            <div class="card" style="height: 100%">
                                <div class="card-body px-0 pt-0 pb-2 mt-3">
                                    <div class="container">
                                        <form action="{{ route('b/add_resep') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="nama_resep" class="form-label text-secondary fs-6">Nama
                                                    Resep <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('nama_resep') is-invalid @enderror"
                                                    id="nama_resep" name="nama_resep" value="{{ old('nama_resep') }}">
                                                @error('nama_resep')
                                                    <script>
                                                        const ErrorNama = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <label for="id_daftarBahan" class="form-label text-secondary fs-6 mb-0">
                                                        Bahan Resep <span class="text-danger">*</span>
                                                    </label>
                                                    <i class="fas fa-solid fa-plus" onclick="addField()"
                                                        style="border: 3px solid green; color: green; padding: 3px; border-radius: 5px; cursor: pointer;"></i>
                                                </div>
                                                <div id="input_fields_wrap">
                                                    @foreach (old('id_daftarBahan', [null]) as $i => $id_daftarBahan)
                                                        <div class="form-group d-flex align-items-center mb-2">
                                                            <select class="form-control me-2" name="id_daftarBahan[]"
                                                                required>
                                                                <option value="">Nama Bahan</option>
                                                                @foreach ($daftarBahans as $dftBhn)
                                                                    <option value="{{ $dftBhn->id }}"
                                                                        {{ $dftBhn->id == $id_daftarBahan ? 'selected' : '' }}>
                                                                        {{ $dftBhn->nama_bahan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="text" name="berat[]" class="form-control me-2"
                                                                placeholder="Berat (Gram)"
                                                                value="{{ old('berat')[$i] ?? '' }}"
                                                                onkeypress="return hanyaAngka(event)" required>

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
                                                <textarea class="form-control @error('langkah') is-invalid @enderror" id="langkah" name="langkah" rows="3">{{ old('langkah') }}</textarea>
                                                @error('langkah')
                                                    <script>
                                                        const ErrorLangkah = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            {{-- <div class="mb-3">
                                                <label for="jumlah_porsi" class="form-label text-secondary fs-6">Porsi
                                                    Makan<span class="text-danger">*</span></label>
                                                <select class="form-control @error('jumlah_porsi') is-invalid @enderror"
                                                    id="jumah_porsi" name="jumlah_porsi">
                                                    <option value="">Jumlah Porsi</option>
                                                    <option value="3"
                                                        {{ old('jumlah_porsi') == '3' ? 'selected' : '' }}>3</option>
                                                    <option value="2"
                                                        {{ old('jumlah_porsi') == '2' ? 'selected' : '' }}>2</option>
                                                    <option value="1"
                                                        {{ old('jumlah_porsi') == '1' ? 'selected' : '' }}>1</option>
                                                </select>
                                                @error('jumlah_porsi')
                                                    <script>
                                                        const ErrorJumlah = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div> --}}
                                            <div class="mb-3">
                                                <label for="min_usia" class="form-label text-secondary fs-6">Minimal
                                                    Usia (bulan)<span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('min_usia') is-invalid @enderror"
                                                    id="min_usia" name="min_usia" value="{{ old('min_usia') }}"
                                                    onkeypress="return hanyaAngka(event)" oninput="cekPanjangInput(this)">
                                                @error('min_usia')
                                                    <script>
                                                        const ErrorMin = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="max_usia" class="form-label text-secondary fs-6">Maksimal
                                                    Usia (bulan)<span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('max_usia') is-invalid @enderror"
                                                    id="max_usia" name="max_usia" value="{{ old('max_usia') }}"
                                                    onkeypress="return hanyaAngka(event)" oninput="cekPanjangInput(this)">
                                                @error('max_usia')
                                                    <script>
                                                        const ErrorMax = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="total_kalori" class="form-label text-secondary fs-6">Total
                                                        Kalori (kcal)</label>
                                                    <input type="text" id="total_kalori" name="total_kalori"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="total_protein"
                                                        class="form-label text-secondary fs-6">Total Protein (g)</label>
                                                    <input type="text" id="total_protein" name="total_protein"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="total_lemak" class="form-label text-secondary fs-6">Total
                                                        Lemak (g)</label>
                                                    <input type="text" id="total_lemak" name="total_lemak"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="total_karbohidrat"
                                                        class="form-label text-secondary fs-6">Total Karbohidrat (g)</label>
                                                    <input type="text" id="total_karbohidrat" name="total_karbohidrat"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="total_serat" class="form-label text-secondary fs-6">Total
                                                        Serat (g)</label>
                                                    <input type="text" id="total_serat" name="total_serat"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="gambar_resep" class="text-secondary fs-6">Gambar Resep
                                                    (Maksimal 2MB)
                                                </label><br>
                                                <input type="file"
                                                    class="form-control @error('gambar_resep') is-invalid @enderror"
                                                    id="gambar_resep" name="gambar_resep" onchange="resepPreview(event)">
                                                @error('gambar_resep')
                                                    <script>
                                                        const ErrorGambar = '{{ $message }}';
                                                    </script>
                                                @enderror
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <a href="/b/resep"
                                                        class="btn btn-sm bg-gradient-danger w-100">Kembali</a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                        class="btn btn-sm bg-gradient-success w-100">Tambah</button>
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
                                                <img src="{{ asset('assets/img/no_image.png') }}" class="mb-3"
                                                    id="resepPreview"
                                                    style="width: 100%; height: 400px; border: 2px solid #d4d4d4; border-radius: 10px;">
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
    </script>
    <script>
        const bahanData = @json($daftarBahans->keyBy('id'));
        const bahanOptions = `{!! collect($daftarBahans)->map(function ($dftBhn) {
                return '<option value="' . $dftBhn->id . '">' . $dftBhn->nama_bahan . '</option>';
            })->implode('') !!}`;
    </script>

    <script>
        function addField() {
            var wrapper = document.getElementById("input_fields_wrap");
            var newField = document.createElement("div");
            newField.className = "form-group d-flex align-items-center mb-2";
            newField.innerHTML = `<select class="form-control me-2" name="id_daftarBahan[]" required>
            <option value="" disabled selected>Nama Bahan</option>
            ${bahanOptions}
        </select>
        <input type="text" name="berat[]" class="form-control me-2" placeholder="Berat" min="1" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)">-</button>`;
            wrapper.appendChild(newField);
        }

        function removeField(button) {
            button.parentNode.remove();
        }
    </script>

    <script>
        function hitungTotal() {
            let totalKalori = 0,
                totalProtein = 0,
                totalLemak = 0,
                totalKarbo = 0,
                totalSerat = 0;

            const fieldGroups = document.querySelectorAll('#input_fields_wrap .form-group');
            fieldGroups.forEach(group => {
                const select = group.querySelector('select[name="id_daftarBahan[]"]');
                const beratInput = group.querySelector('input[name="berat[]"]');
                if (select && beratInput && select.value && beratInput.value) {
                    const bahan = bahanData[select.value];
                    const berat = parseFloat(beratInput.value);
                    if (bahan && berat) {
                        totalKalori += (bahan.kalori * berat) / 100;
                        totalProtein += (bahan.protein * berat) / 100;
                        totalLemak += (bahan.lemak * berat) / 100;
                        totalKarbo += (bahan.karbohidrat * berat) / 100;
                        totalSerat += (bahan.serat * berat) / 100;
                    }
                }
            });
            document.getElementById('total_kalori').value = totalKalori === 0 ? '' : totalKalori.toFixed(2);
            document.getElementById('total_protein').value = totalProtein === 0 ? '' : totalProtein.toFixed(2);
            document.getElementById('total_lemak').value = totalLemak === 0 ? '' : totalLemak.toFixed(2);
            document.getElementById('total_karbohidrat').value = totalKarbo === 0 ? '' : totalKarbo.toFixed(2);
            document.getElementById('total_serat').value = totalSerat === 0 ? '' : totalSerat.toFixed(2);
        }

        document.addEventListener('input', function(e) {
            if (e.target.matches('select[name="id_daftarBahan[]"], input[name="berat[]"]')) {
                hitungTotal();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            hitungTotal();
        });
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
        if (typeof ErrorNama !== 'undefined' || typeof ErrorLangkah !== 'undefined' || typeof ErrorJumlah !== 'undefined' ||
            typeof ErrorPorsi !== 'undefined' ||
            typeof ErrorMin !== 'undefined' || typeof ErrorMax !== 'undefined' || typeof ErrorBahan !== 'undefined' ||
            typeof ErrorBerat !== 'undefined' || typeof ErrorGambar !== 'undefined'
        ) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
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
            if (typeof ErrorNama !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorNama,
                });
            } else if (typeof ErrorLangkah !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorLangkah,
                });
            } else if (typeof ErrorJumlah !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorJumlah,
                });
            } else if (typeof ErrorMin !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorMin,
                });
            } else if (typeof ErrorMax !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorMax,
                });
            } else if (typeof ErrorBahan !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorBahan,
                });
            } else if (typeof ErrorBerat !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorBerat,
                });
            } else if (typeof ErrorGambar !== 'undefined') {
                Toast.fire({
                    icon: 'warning',
                    title: ErrorGambar,
                });
            }
        }
    </script>
@endsection
