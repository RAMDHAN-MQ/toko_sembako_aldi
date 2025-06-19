@extends('layouts.master')

@section('content')

<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 250px;
        background-color: #198754;
        /* Warna hijau */
        color: white;
        padding: 15px;
    }

    .sidebar-link:hover {
        background-color: white !important;
        color: green !important;
        border-radius: 5px;
    }

    .content {
        margin-left: 270px;
        /* Sesuaikan agar tidak tertutup sidebar */
        padding: 20px;
        width: calc(100% - 270px);
    }

    .table-container {
        max-height: 80vh;
        overflow-y: auto;
    }

    .btn-custom {
        border: 2px solid #198754;
        color: #198754;
        transition: all 0.3s ease;
    }

    .btn-custom:hover,
    .btn-custom:active,
    .btn-custom:focus {
        background-color: #198754 !important;
        color: white !important;
        border-color: #198754 !important;
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

<!-- Main Content -->
<div class="content">
    <div class="flex-grow-1 p-4">
        <!-- pencarian berdasarkan tanggal -->
        <div class="mb-3 d-flex">
            <form action="{{ route('laporan.cari') }}" method="GET" class="d-flex">
                <input type="date" name="tanggal_mulai" class="form-control me-2" value="{{ request('tanggal_mulai') }}">
                <span class="align-self-center me-2">sampai</span>
                <input type="date" name="tanggal_akhir" class="form-control me-2" value="{{ request('tanggal_akhir') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('laporan') }}" class="btn btn-secondary ms-2">Reset</a>
            </form>
        </div>
        <!-- export -->
        <div class="mb-3 d-flex">
            <a href="{{ route('laporan.pdf') }}" class="btn btn-danger me-2" id="exportPdf" target="_blank">Export PDF</a>
            <a href="{{ route('laporan.excel')  }}" class="btn btn-success me-2" id="exportExcel">Export Excel</a>
            <a href="{{ route('laporan.chart') }}" class="btn btn-warning me-2" id="chart">Chart</a>
            <a href="{{ route('laporan.produkTerlaris') }}" class="btn btn-danger me-2">Produk Terlaris</a>
            <a href="{{ route('laporan.ongkir') }}" class="btn btn-success">Laporan Ongkir</a>
        </div>
        <table class="table table-bordered table-striped table-section" id="tabel-laporan">
            <thead class="table-success">
                <tr>
                    <th>No.</th>
                    <th>ID Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($dataTransaksi->where('status','selesai') as $dataT)
                @foreach ($dataDetail->where('transaksi_id', $dataT->id) as $dataD)
                @foreach ($dataUser->where('id', $dataT->user_id) as $dataU)
                @foreach ($dataProduk->where('id', $dataD->produk_id) as $dataP)
                <tr>
                    <td>{{ $no++ }}.</td>
                    <td>{{ $dataT->id }}</td>
                    <td>{{ $dataT->user->name }}</td>
                    <td>{{ $dataD->produk->nama_produk }}</td>
                    <td class="text-center">{{ $dataD->jumlah }}</td>
                    <td class="text-end">Rp{{ number_format($dataD->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-end">Rp{{ number_format($dataD->jumlah * $dataD->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $dataD->created_at }}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endforeach
                
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

            </tbody>
        </table>
    </div>
    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabel-laporan').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 20, 50, 100],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ entri",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                },
                "search": "Cari:"
            }
        });
    });


    function toggleArrow(element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('down');
    }

</script>

@endsection