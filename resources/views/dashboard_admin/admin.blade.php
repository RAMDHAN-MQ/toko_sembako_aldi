@extends('layouts.master')

@section('content')

<style>
    .sidebar-link:hover {
        background-color: white !important;
        color: green !important;
        border-radius: 5px;
    }
    .submenu-bg {
        background-color: #d4edda;
        border-radius: 5px;
    }

    .rotate {
        transition: transform 0.3s ease;
    }

    .rotate.down {
        transform: rotate(180deg);
    }
</style>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-success text-white vh-100 p-3" style="width: 250px;">
        <h5 class="text-uppercase fw-bold" href="admin"><a href="admin" class="text-white text-decoration-none">Dashboard Admin</a></h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="tampil-produk" class="nav-link text-white sidebar-link">Data Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white sidebar-link d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#stokSubmenu" role="button"
                    aria-expanded="false" aria-controls="stokSubmenu"
                    onclick="toggleArrow(this)">
                    <span>Data Stok</span>
                    <i class="bi bi-chevron-down rotate"></i>
                </a>
                <div class="collapse ps-3 submenu-bg" id="stokSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="stok-masuk" class="nav-link text-dark sidebar-link">Stok Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="stok-keluar" class="nav-link text-dark sidebar-link">Stok Keluar</a>
                        </li>
                        <li class="nav-item">
                            <a href="stok-opname" class="nav-link text-dark sidebar-link">Stok Opname</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="tampil-kategori" class="nav-link text-white sidebar-link">Data Kategori</a>
            </li>
            <li class="nav-item">
                <a href="tampil-User" class="nav-link text-white sidebar-link">Data User</a>
            </li>
            <li class="nav-item">
                <a href="tampil-transaksi" class="nav-link text-white sidebar-link">Data Transaksi</a>
            </li>
            <li class="nav-item">
                <a href="laporan" class="nav-link text-white sidebar-link">Laporan</a>
            </li>
            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 text-start">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Selamat Datang di Dashboard Admin</h2>
        <p>Silakan pilih menu di sebelah kiri.</p>
    </div>
</div>

<script>
    function toggleArrow(element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('down');
    }
</script>
@endsection