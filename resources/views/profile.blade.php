@extends('layouts.master')

@section('title', 'Aplikasi Laravel')

@section('content')

<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-light p-3" style="width: 200px; min-height: 100vh;">
        <a class="btn btn-success w-100 mb-3" href="order">KEMBALI</a>
        <ul class="nav flex-column nav-pills">
            <li class="nav-item">
                <a class="nav-link active" id="update-tab" data-bs-toggle="pill" href="#update-content"><i class="fa-regular fa-user"></i> Akun Saya</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="histori-tab" data-bs-toggle="pill" href="#histori-content"><i class="fa-solid fa-spinner fa-spin"></i> Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dikirim-tab" data-bs-toggle="pill" href="#dikirim-content"><i class="fa-solid fa-truck-fast"></i> Dikirim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="diterima-tab" data-bs-toggle="pill" href="#diterima-content"><i class="fa-solid fa-envelope-open"></i> Diterima</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="flex-grow-1 p-3">
        <div class="tab-content">
            <div class="tab-pane fade show active bg-white p-3" id="update-content">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Foto Profil -->
                    <div class="form-group text-center mb-4">
                        <img id="preview-image" src="{{ asset($user->foto ?? 'images/default_profile.png') }}"
                            alt="Foto Profil"
                            class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #000;">

                        <div class="mt-2">
                            <label for="foto" class="btn btn-primary btn-sm">Pilih Foto</label>
                            <input type="file" name="foto" accept="image/*" class="form-control-file d-none" id="foto" onchange="previewFile()">
                        </div>
                    </div>

                    <script>
                        function previewFile() {
                            var preview = document.getElementById('preview-image');
                            var file = document.getElementById('foto').files[0];
                            var reader = new FileReader();

                            reader.onloadend = function() {
                                preview.src = reader.result;
                            }

                            if (file) {
                                reader.readAsDataURL(file);
                            } else {
                                preview.src = "{{ asset('images/' . ($user->foto ?? 'default_profile.png')) }}";
                            }
                        }
                    </script>

                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password (kosongkan jika tidak ingin diubah)</label>
                        <input class="form-control" type="password" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                        <input class="form-control" oninput="cariRekomendasi(this.value)" type="text" name="alamat" id="alamat" value="{{ old('alamat', $user->alamat) }}">
                    </div>
                    <br>
                    <ul id="suggestions" class="list-group" style="position: absolute; z-index: 1000;"></ul>

                    <button type="button" class="btn btn-info" onclick="cariLokasi()">Cari</button>

                    <p id="koordinat" class="mt-2"></p>

                    <div id="map" style="height: 400px; width: 100%;"></div>

                    <script>
                        var map = L.map('map').setView([-7.8024121, 111.98003444926701], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        var marker;

                        function cariRekomendasi(query) {
                            if (query.length < 3) {
                                document.getElementById('suggestions').innerHTML = "";
                                return;
                            }

                            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&bounded=1&viewbox=110.55,-5.85,114.62,-9.0`;

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    let suggestionBox = document.getElementById('suggestions');
                                    suggestionBox.innerHTML = "";

                                    data.forEach(item => {
                                        let li = document.createElement("li");
                                        li.classList.add("list-group-item");
                                        li.style.cursor = "pointer";
                                        li.textContent = item.display_name;
                                        li.onclick = function() {
                                            document.getElementById('alamat').value = item.display_name;
                                            document.getElementById('suggestions').innerHTML = "";
                                            tampilkanLokasi(item.lat, item.lon, item.display_name);
                                        };
                                        suggestionBox.appendChild(li);
                                    });
                                })
                                .catch(error => console.error("Error fetching suggestions:", error));
                        }

                        function cariLokasi() {
                            var alamat = document.getElementById("alamat").value.trim();

                            if (alamat === "") {
                                alert("Masukkan alamat terlebih dahulu!");
                                return;
                            }

                            var apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}`;

                            fetch(apiUrl, {
                                    headers: {
                                        'User-Agent': 'my-app'
                                    } // Beberapa server butuh User-Agent
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error("Gagal mengambil data lokasi. Coba lagi nanti.");
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log("Data lokasi:", data);

                                    if (data.length === 0) {
                                        alert("Lokasi tidak ditemukan! Coba masukkan alamat yang lebih spesifik.");
                                        return;
                                    }

                                    var lat = parseFloat(data[0].lat);
                                    var lon = parseFloat(data[0].lon);
                                    document.getElementById("koordinat").innerText = `Koordinat: ${lat}, ${lon}`;

                                    // Update tampilan peta
                                    map.setView([lat, lon], 13);

                                    // Hapus marker lama jika ada
                                    if (marker) {
                                        map.removeLayer(marker);
                                    }

                                    // Tambahkan marker baru
                                    marker = L.marker([lat, lon]).addTo(map)
                                        .bindPopup(`Lokasi: ${alamat}`)
                                        .openPopup();
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    alert(error.message);
                                });
                        }
                    </script>
                    <br>
                    <div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade bg-white p-3" id="histori-content">
                <h4>Pending</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataTransaksi->where('status','pending') ->sortByDesc('created_at') as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ ucfirst($transaction->status) }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                                    Detail
                                </button>
                                <button class="btn btn-success bayar-transaksi"
                                    data-snap-token="{{ $transaction->snap_token }}"
                                    data-transaction-id="{{ $transaction->id }}">
                                    Bayar
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                        <p><strong>Produk:</strong></p>
                                        @foreach ($transaction->details as $detail)
                                        <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                        @endforeach
                                        <p><strong>Ongkir:</strong> Rp 16.000</p>
                                        <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                        <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                        <p><strong>Tanggal:</strong> {{ $transaction->created_at }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade bg-white p-3" id="dikirim-content">
                <h4>Dikirim</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataTransaksi->where('status','dikirim') ->sortByDesc('created_at') as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ ucfirst($transaction->status) }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                        <p><strong>Produk:</strong></p>
                                        @foreach ($transaction->details as $detail)
                                        <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                        @endforeach
                                        <p><strong>Ongkir:</strong> Rp 16.000</p>
                                        <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                        <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                        <p><strong>Tanggal Bayar:</strong> {{ $transaction->tanggal_bayar }}</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade bg-white p-3" id="diterima-content">
                <h4>Diterima</h4>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataTransaksi->where('status','selesai') ->sortByDesc('created_at') as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ ucfirst($transaction->status) }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->harga, 0, ',', '.') }}</td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $transaction->id }}">
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal-{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel">Detail Transaksi #{{ $transaction->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Pesanan:</strong> {{ $transaction->id }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                        <p><strong>Produk:</strong></p>
                                        @foreach ($transaction->details as $detail)
                                        <p>{{ $detail->produk->nama_produk }} = {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }} = Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                        @endforeach
                                        <p><strong>Ongkir:</strong> Rp 16.000</p>
                                        <p><strong>Harga:</strong> Rp {{ number_format($transaction->harga, 0, ',', '.') }}</p>
                                        <p><strong>Snap Token:</strong> {{ $transaction->snap_token }}</p>
                                        <p><strong>Tanggal Bayar:</strong> {{ $transaction->tanggal_bayar }}</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Script Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.querySelectorAll('.bayar-transaksi').forEach(button => {
        button.addEventListener('click', function() {
            let snapToken = this.getAttribute('data-snap-token');
            let transactionId = this.getAttribute('data-transaction-id'); // Tambahkan ID transaksi

            if (!snapToken) {
                alert("Token pembayaran tidak tersedia.");
                return;
            }

            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    alert("Pembayaran berhasil!");

                    // Kirim request ke backend untuk update status
                    fetch("{{ route('update.status') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                transaction_id: transactionId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Status transaksi diperbarui:", data);
                            location.reload();
                        })
                        .catch(error => console.error("Error:", error));
                },
                onError: function(result) {
                    console.log('Payment Failed:', result);
                    alert("Pembayaran gagal. Silakan coba lagi.");
                },
            });
        });
    });
</script>

@endsection