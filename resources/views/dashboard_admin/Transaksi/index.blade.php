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

<div class="content">
    <h2>DATA TRANSAKSI</h2>
    <a href="#" class="btn btn-custom" onclick="showTable('pending')">Pending</a>
    <a href="#" class="btn btn-custom" onclick="showTable('dikirim')">Dikirim</a>
    <a href="#" class="btn btn-custom" onclick="showTable('selesai')">Selesai</a>
    <br><br>

    <div class="table-container">
        <table class="table table-bordered table-striped table-section" id="tabel-pending">
            <thead class="table-success">
                <tr>
                    <th style="width: 1%">Id.</th>
                    <th style="width: 5%">Pembeli</th>
                    <th style="width: 5%">Harga</th>
                    <th style="width: 1%">Jumlah Beli</th>
                    <th style="width: 5%">Status</th>
                    <th style="width: 5%">Tanggal Transaksi</th>
                    <th style="width: 5%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dataTransaksi->where('status','pending') ->sortByDesc('created_at') as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                    <td>{{ $transaction->details->sum('jumlah') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                            Detail
                        </button>
                    </td>
                </tr>
                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                <p><strong>Produk:</strong></p>
                                @foreach ($transaction->details as $detail)
                                <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                @endforeach
                                <p><strong>Ongkir:</strong>Rp 16.000</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                <p><strong>Tanggal:</strong> {{ $transaction->created_at }}</p>
                                <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                                <p><strong>Alamat:</strong> {{ $transaction->user->alamat }}</p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <table class="table table-bordered table-striped table-section" id="tabel-dikirim">
            <thead class="table-success">
                <tr>
                    <th style="width: 1%">Id.</th>
                    <th style="width: 5%">Pembeli</th>
                    <th style="width: 5%">Harga</th>
                    <th style="width: 1%">Jumlah Beli</th>
                    <th style="width: 5%">Status</th>
                    <th style="width: 5%">Tanggal Transaksi</th>
                    <th style="width: 5%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dataTransaksi->where('status','dikirim') ->sortByDesc('created_at') as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                    <td>{{ $transaction->details->sum('jumlah') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <form>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                                    Detail
                                </button>
                            </form>
                            <form action="{{ url('/transaksi/sampai/' . $transaction->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Sampai</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                <p><strong>Produk:</strong></p>
                                @foreach ($transaction->details as $detail)
                                <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                @endforeach
                                <p><strong>Ongkir:</strong>Rp 16.000</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                <p><strong>Tanggal Bayar:</strong> {{ $transaction->tanggal_bayar }}</p>
                                <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                                <p><strong>Alamat:</strong> {{ $transaction->user->alamat }}</p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <table class="table table-bordered table-striped table-section  " id="tabel-selesai">
            <thead class="table-success">
                <tr>
                    <th style="width: 1%">Id.</th>
                    <th style="width: 5%">Pembeli</th>
                    <th style="width: 5%">Harga</th>
                    <th style="width: 1%">Jumlah Beli</th>
                    <th style="width: 5%">Status</th>
                    <th style="width: 5%">Tanggal Transaksi</th>
                    <th style="width: 5%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dataTransaksi->where('status','selesai') ->sortByDesc('created_at') as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                    <td>{{ $transaction->details->sum('jumlah') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                            Detail
                        </button>
                    </td>
                </tr>
                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                <p><strong>Produk:</strong></p>
                                @foreach ($transaction->details as $detail)
                                <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                @endforeach
                                <p><strong>Ongkir:</strong>Rp 16.000</p>
                                <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                <p><strong>Tanggal Bayar:</strong> {{ $transaction->tanggal_bayar }}</p>
                                <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                                <p><strong>Alamat:</strong> {{ $transaction->user->alamat }}</p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function showTable(status) {
        // Sembunyikan semua tabel
        document.getElementById('tabel-pending').style.display = 'none';
        document.getElementById('tabel-dikirim').style.display = 'none';
        document.getElementById('tabel-selesai').style.display = 'none';

        // Tampilkan tabel sesuai dengan status yang dipilih
        document.getElementById('tabel-' + status).style.display = 'block';
    }

    // Tampilkan tabel pending secara default
    document.addEventListener('DOMContentLoaded', function() {
        showTable('pending');
    });



    $(document).on('click', '.btn-sampai', function() {
        let transactionId = $(this).data('id');

        $.ajax({
            url: `/update-status/${transactionId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload(); // Reload halaman untuk memperbarui tampilan status
                }
            },
            error: function() {
                alert('Terjadi kesalahan, coba lagi nanti.');
            }
        });
    });


    function toggleArrow(element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('down');
    }

</script>
@endsection