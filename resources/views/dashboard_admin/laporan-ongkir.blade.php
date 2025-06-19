@extends('layouts.master')

@section('content')

<style>
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
</style>

<div class="container mt-4">
    <h4 class="mb-3">📦 Laporan Ongkir</h4>
    
    <a href="{{ route('laporan') }}" class="btn btn-danger me-2">← Kembali</a>
    <a href="{{ route('laporan.ongkir.excel') }}" class="btn btn-success me-2" id="exportExcel">Export Excel</a>
    <a href="{{ route('laporan.ongkir.pdf') }}" class="btn btn-danger me-2" id="exportPdf" target="_blank">Export PDF</a>
    <br><br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="tabel-ongkir">
            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Biaya Ongkir</th>
                    <th>Tanggal Kirim</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($dataTransaksi->where('status','selesai') as $dataT)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dataT->id }}</td>
                    <td>{{ $dataT->user->name }}</td>
                    <td>Rp{{ number_format(16000, 0, ',', '.') }}</td>
                    <td>{{ $dataT->updated_at->format('d-m-Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabel-ongkir').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 20, 50],
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
</script>

@endsection
