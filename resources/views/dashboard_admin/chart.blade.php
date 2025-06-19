@extends('layouts.master')

@section('content')
<div class="container">
    <br>
    <a href="{{ url('laporan') }}" class="btn btn-danger">Kembali</a>
    <br>
    <h2 class="text-center">Laporan Pendapatan Perbulan</h2>
    <canvas id="pendapatanChart"></canvas>
</div>
<script>
    var ctx = document.getElementById('pendapatanChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulanLengkap) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($pendapatanLengkap) !!},
                backgroundColor: 'rgba(255, 165, 0, 0.7)',
                borderColor: 'rgba(255, 165, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
