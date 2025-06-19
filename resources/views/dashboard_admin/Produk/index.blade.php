@extends('layouts.master')

@section('content')

<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 250px;
        background-color: #198754; /* Warna hijau */
        color: white;
        padding: 15px;
    }

    .sidebar-link:hover {
        background-color: white !important;
        color: green !important;
        border-radius: 5px;
    }

    .content {
        margin-left: 270px; /* Sesuaikan agar tidak tertutup sidebar */
        padding: 20px;
        width: calc(100% - 270px);
    }

    .table-container {
        max-height: 80vh;
        overflow-y: auto;
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

<div class="sidebar">
    <h5 class="text-uppercase fw-bold"><a href="admin" class="text-white text-decoration-none">Dashboard Admin</a></h5>
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

<div class="content">
    <h2>DATA PRODUK</h2>
    <div class="d-flex mb-3">
        <a href="{{route('produk.create')}}" class="btn btn-success me-2">+Tambah Data</a>
    </div>
    
    <div class="table-container">
        <table class="table table-bordered table-striped" id="tabel-produk">
        <thead class="table-success">
            <tr>
                <th style="width: 1%">No.</th>
                <th style="width: 5%">Kode Produk</th>
                <th style="width: 5%">Kategori</th>
                <th style="width: 5%">Nama Produk</th>
                <th style="width: 5%">Harga</th>
                <th style="width: 5%">Stok</th>
                <th style="width: 5%">Foto</th>
                <th style="width: 5%">Aksi</th>
            </tr>
        </thead>

            <tbody>
                @foreach ($dataproduk as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->kategori->nama_kategori }}</td>
                    <td>{{ $data->nama_produk }}</td>
                    <td class="text-end">Rp {{ number_format($data->harga, 0, ',','.') }}</td>
                    <td>{{ $data->stok }}</td>
                    <td><img src="{{ asset($data->foto) }}" alt="Foto Produk" width="80"></td>
                    <td>
                        <form action="{{route('produk.delete', $data->id)}}" method="post">@csrf
                            <a href="{{route('produk.edit', $data->id)}}" class="btn btn-warning">Ubah</a>
                            <button class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleArrow(element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('down');
    }
</script>
@endsection
