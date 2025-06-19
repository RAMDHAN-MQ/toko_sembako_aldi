@extends('layouts.master')

@section('title', 'Aplikasi Laravel')

@section('content')

<div class="container">
    <h2>Tambah Produk</h2>
    <form action="{{route('produk.store')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label><br>
            <select name="kategori" class="form-control"required>
                @foreach ($kategori as $category)
                <option value="{{$category->id}}">{{ $category->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" id="stok" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="foto" id="foto" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <a href="tampil-produk" class="btn btn-danger">Batal</a>
            <button type="submit" class="btn btn-dark">Simpan</button>
        </div>
    </form>
</div>




@endsection
