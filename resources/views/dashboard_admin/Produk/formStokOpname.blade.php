@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h4>Tambah Data Stok Opname</h4>

    <form method="POST" action="{{ route('stok-opname.store') }}">
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
            <input type="text" name="keterangan" class="form-control" placeholder="Masukkan Keterangan">
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProduk">Tambah Produk</button>
        </div>



        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Nama Produk</th>
                    <th>Stok Sistem</th>
                    <th>Stok Fisik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="produkTerpilihBody">
            </tbody>
        </table>

        <!-- Modal Produk -->
        <div class="modal fade" id="modalProduk" tabindex="-1" aria-labelledby="modalProdukLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" id="searchProduk" class="form-control" placeholder="Cari nama produk...">
                        </div>

                        <div style="max-height: 400px; overflow-y: auto;">


                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Nama Produk</th>
                                        <th>Stok Sistem</th>
                                        <th>Stok Fisik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produkList as $produk)
                                    <tr>
                                        <td><input type="checkbox" class="produk-checkbox" value="{{ $produk->id }}"></td>
                                        <td>{{ $produk->nama_produk }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td>
                                            <input type="number" class="form-control" value="0" min="0" disabled>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btnTambahProduk">Tambah</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('stok-opname') }}" class="btn btn-danger">Kembali</a>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll(".produk-checkbox");

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                const input = this.closest("tr").querySelector("input[type='number']");
                input.disabled = !this.checked;
            });
        });

        document.getElementById("checkAll").addEventListener("change", function() {
            const checked = this.checked;
            checkboxes.forEach(cb => {
                cb.checked = checked;
                const input = cb.closest("tr").querySelector("input[type='number']");
                input.disabled = !checked;
            });
        });

        document.getElementById("searchProduk").addEventListener("input", function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#modalProduk tbody tr");

            rows.forEach(function(row) {
                const namaProduk = row.children[1].textContent.toLowerCase();
                row.style.display = namaProduk.includes(filter) ? "" : "none";
            });
        });

        document.getElementById("btnTambahProduk").addEventListener("click", function() {
            const tbody = document.getElementById("produkTerpilihBody");

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const id = cb.value;
                    const sudahAda = [...tbody.querySelectorAll("input[name='produk_id[]']")].some(input => input.value === id);
                    if (sudahAda) return;

                    const row = cb.closest("tr");
                    const nama = row.children[1].innerText;
                    const stokSistem = row.children[2].innerText;
                    const stokFisikInput = row.querySelector("input[type='number']");
                    const stokFisik = stokFisikInput.value;

                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                    <td>
                        ${nama}
                        <input type="hidden" name="produk_id[]" value="${id}">
                    </td>
                    <td>${stokSistem}</td>
                    <td>
                        <input type="number" name="stok_fisik[${id}]" class="form-control" value="${stokFisik}" min="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus-produk">Hapus</button>
                    </td>
                `;
                    tbody.appendChild(tr);
                }
            });

            tbody.querySelectorAll(".btn-hapus-produk").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    this.closest("tr").remove();
                });
            });

            bootstrap.Modal.getInstance(document.getElementById('modalProduk')).hide();
        });
    });
</script>

@endsection