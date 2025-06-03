<div class="min-height-300 position-fixed w-100" style="background: linear-gradient(to bottom right, #1E439C, #43D8F8);">
</div>
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/k/dashboard">
            <img src="{{ asset('assets/img/logo pintasi.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">PINTASI</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/k/dashboard">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        {{-- <img src="{{ asset('assets/img/dashboard.png') }}" style="height: 2.75vh;"> --}}
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/k/ibu">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        {{-- <img src="{{ asset('assets/img/anakasuh.png') }}" style="height: 2.75vh;"> --}}
                    </div>
                    <span class="nav-link-text ms-1">Data Akun Ibu</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/k/bayi">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    </div>
                    <span class="nav-link-text ms-1">Data Bayi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/k/pemeriksaan">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    </div>
                    <span class="nav-link-text ms-1">Data Pemeriksaan</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
