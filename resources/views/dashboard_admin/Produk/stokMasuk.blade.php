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

    <div class="flex-grow-1 p-4">
        <h2>Stok Masuk</h2>

        <div class="">
            <a href="" class="btn btn-danger">Export PDF</a>
            <a href="{{ route('stok-masuk.form') }}" class="btn btn-success me-2">Tambah Data</a>
        </div>
        <br>
        <table class="table table-bordered table-striped table-section" id="tabel-laporan">
            <thead class="table-success">
                <tr>
                    <th>No.</th>
                    <th>ID Histori</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($historiStokMasuk->where('tipe','Stok Masuk') as $histori)
                <tr>
                    <td>{{ $no++ }}.</td>
                    <td>{{ $histori ->id }}</td>
                    <td>{{ $histori ->keterangan }}</td>
                    <td>{{ $histori ->created_at->format('Y-m-d') }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $histori->id }}">
                            Detail
                        </button>
                    </td>
                </tr>


                @endforeach
            </tbody>
        </table>

        @foreach ($historiStokMasuk as $histori)
        <div class="modal fade" id="detailModal-{{ $histori->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Stok #{{ $histori->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Beli</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histori->detailStokMasuk as $stok)
                                <tr>
                                    <td>{{ $stok->produk->nama_produk }}</td>
                                    <td class="text-end">Rp{{ number_format($stok->harga, 0, ',', '.') }}</td>
                                    <td>{{ $stok->qty }}</td>
                                    <td class="text-end">Rp{{ number_format($stok->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

<script>
    function toggleArrow(element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('down');
    }
</script>
@endsection