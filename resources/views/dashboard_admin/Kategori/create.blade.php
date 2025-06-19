@extends('layouts.master')

@section('title', 'Aplikasi Laravel')

@section('content')

<div class="container">
    <h2>Tambah Kategori</h2>
    <form action="{{route('kategori.store')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" id="nama_kategori" required>
        </div>

        <div class="d-flex gap-2">
            <a href="{{url('tampil-kategori')}}" class="btn btn-danger">Batal</a>
            <button type="submit" class="btn btn-dark">Simpan</button>
        </div>
    </form>
</div>




@endsection
