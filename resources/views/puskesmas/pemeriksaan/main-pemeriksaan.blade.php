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
              <button type="button" class="btn btn-sm bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Tambah Data</button>
            </div>
            <!-- <div class="col-lg-6 col-md-6 col-sm-12 px-4">
            </div> -->
          </div>
          <div class="card-body px-0 pt-0 pb-2 mt-3">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Berat Badan(kg)</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Tinggi Badan(cm)</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Tanggal Periksa</th>
                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pemeriksaans as $pemeriksaan)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0">{{ $pemeriksaan->bayi->nama_bayi }}</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">{{ $pemeriksaan->bb }}</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">{{ $pemeriksaan->tb }}</p>
                    </td>
                    <td>
                      <p class="text-x font-weight-bold mb-0 text-center">{{ $pemeriksaan->tgl_periksa }}</p>
                    </td>
                    <td class="align-middle text-sm">
                      <form action="{{ route('delete_pemeriksaan', $pemeriksaan->id) }}" method="post">
                        <a href="{{ route('detail_pemeriksaan', $pemeriksaan->id) }}" class="btn btn-sm bg-gradient-primary">Detail</a>
                        <a href="{{ route('edit_pemeriksaan', $pemeriksaan->id) }}" class="btn btn-sm bg-gradient-success">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm bg-gradient-danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3" class="text-center">Data pemeriksaan belum Tersedia.</td>
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
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                  @foreach($bayis as $bayi)
                    <option value="{{ $bayi->id }}">{{ $bayi->nama_bayi }}</option>
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
          window.location.href = "{{ url('/tambah_pemeriksaan') }}/" + idBayi;
      } else {
          alert('Silakan pilih bayi terlebih dahulu.');
      }
    });
  </script>

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
