@extends('layouts.master')

@section('content')


<div class="container mt-4">
    <a href="{{ route('laporan') }}" class="btn btn-danger mb-3">Kembali</a>
    <div class="card shadow rounded-4">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0 text-center"><i class="bi bi-star-fill me-2"></i>10 Produk Paling Laris</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-success">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produkInfo as $index => $produk)
                            <tr>
                                <td><span class="badge bg-primary">{{ $index + 1 }}</span></td>
                                <td>{{ $produk['nama_produk'] }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $produk['total_terjual'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection