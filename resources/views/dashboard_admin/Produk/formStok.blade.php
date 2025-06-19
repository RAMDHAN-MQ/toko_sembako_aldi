@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Tambah Data Stok Masuk</h4>

    <form method="POST" action="{{ route('stok-masuk.store') }}">
        @csrf

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="mb-3">
            <label for="created_at">Tanggal:</label>
            <input type="date" name="created_at" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan">Keterangan:</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Masukkan Keterangan ">
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" id="addRow">Tambah Baris</button>
        </div>
        <table class="table table-bordered" id="produkTable">
            <thead class="table-success">
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produkBody">
                <tr>
                    <td class="position-relative">
                        <input type="text" name="nama_produk[]" class="form-control nama-produk" autocomplete="off">
                        <input type="hidden" name="produk_id[]" class="produk-id">
                        <div class="produkList list-group position-absolute w-100 z-3" style="max-height: 200px; overflow-y: auto;"></div>
                    </td>
                    <td><input type="number" name="harga[]" class="form-control" required></td>
                    <td><input type="number" name="qty[]" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>


        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('stok-masuk') }}" class="btn btn-danger">Kembali</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Tambah baris baru
        $('#addRow').click(function() {
            let row = `
        <tr>
            <td class="position-relative">
                <input type="text" name="nama_produk[]" class="form-control nama-produk" autocomplete="off">
                <input type="hidden" name="produk_id[]" class="produk-id">
                <div class="produkList list-group position-absolute w-100 z-3" style="max-height: 200px; overflow-y: auto;"></div>
            </td>
            <td><input type="number" name="harga[]" class="form-control" required></td>
            <td><input type="number" name="qty[]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
        </tr>`;
            $('#produkBody').append(row);
        });

        // Hapus baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Auto-complete nama produk
        $(document).on('keyup', '.nama-produk', function() {
            let input = $(this);
            let query = input.val();
            let list = input.siblings('.produkList');

            if (query.length >= 1) {
                $.ajax({
                    url: "{{ route('produk.search') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        list.fadeIn().html(data);
                    }
                });
            } else {
                list.fadeOut();
            }
        });

        // Pilih produk dari dropdown
        $(document).on('click', '.produk-item', function() {
            let item = $(this);
            let tr = item.closest('td');
            let input = tr.find('.nama-produk');
            let hidden = tr.find('.produk-id');

            input.val(item.text());
            hidden.val(item.data('id'));
            tr.find('.produkList').fadeOut();
        });

        // Klik luar dropdown
        $(document).click(function(e) {
            if (!$(e.target).closest('.nama-produk, .produkList').length) {
                $('.produkList').fadeOut();
            }
        });
    });
</script>
@endpush