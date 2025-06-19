<html>

<head>
    <title>Cetak PDF</title>
</head>

<body>
    <style type="text/css">

    </style>

    <h4 align="center">LAPORAN PEMBELIAN <br> TOKO ALDI SEMBAKO</h4>
    <table class="table table-bordered table-striped table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>ID Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($dataTransaksi->where('status','selesai') as $dataT)
            @foreach ($dataDetail->where('transaksi_id', $dataT->id) as $dataD)
            @foreach ($dataUser->where('id', $dataT->user_id) as $dataU)
            @foreach ($dataProduk->where('id', $dataD->produk_id) as $dataP)
            <tr>
                <td>{{ $no++ }}.</td>
                <td>{{ $dataT->id }}</td>
                <td>{{ $dataT->user->name }}</td>
                <td>{{ $dataD->produk->nama_produk }}</td>
                <td class="text-center">{{ $dataD->jumlah }}</td>
                <td class="text-end">Rp{{ number_format($dataD->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-end">Rp{{ number_format($dataD->jumlah * $dataD->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $dataD->created_at }}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>
    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>