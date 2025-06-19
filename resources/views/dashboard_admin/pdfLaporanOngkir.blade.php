<html>

<head>
    <title>Cetak PDF</title>
</head>

<body>
    <style type="text/css">

    </style>

    <h4 align="center">Laporan Ongkir <br> TOKO ALDI SEMBAKO</h4>
    <div class="table-container" style="display:flex; justify-content: center;">
        <table class="table table-bordered table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
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


    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>