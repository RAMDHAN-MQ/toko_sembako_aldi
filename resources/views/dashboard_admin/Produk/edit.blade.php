@extends('layouts.master')

@section('title', 'Aplikasi Laravel')

@section('content')

<div class="container">
    <h2>Edit Data Produk</h2>
    <form action="{{route('produk.update', $data->id)}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" id="kategori" value="{{$data->kategori_id}}" required>
                @foreach ($kat as $cat)
                <option value="{{$cat -> id }}" {{ $cat->id == $data->kategori_id ? 'selected' : ''}}>
                    {{ $cat->nama_kategori }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{$data->nama_produk}}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{$data->harga}}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="foto" id="foto" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <a href="{{url('tampil-produk')}}" class="btn btn-danger">Batal</a>
            <button type="submit" class="btn btn-dark">Simpan</button>
        </div>
    </form>
</div>




@endsection
