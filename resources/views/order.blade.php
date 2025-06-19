@extends('layouts.master')

@section('title', 'Orders')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #2E8B57;">

    <div class="container d-flex align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="{{url('order')}}">
            <img src="/images/logo-2.png" width="40" class="me-2">
            <span class="fw-bold text-white">Toko Sembako</span>
        </a>

        <form action="{{ route('search') }}" method="GET" class="d-flex flex-grow-1 align-items-center justify-content-center my-auto" style="max-width: 700px; height: 100%;">
            <input type="text" name="q" class="form-control me-2 w-100" placeholder="Cari produk, kebutuhan pokok" style="height: 40px;">
            <button class="btn btn-light fw-bold px-3 py-2" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        @auth
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}</button>
            <img src="{{ asset(Auth::user()->foto ?? 'images/default_profile.png') }}"
                alt="Foto Profil"
                class="rounded-circle me-2"
                style="width: 35px; height: 35px; object-fit: cover; border: 1px solid #fff;">
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="profile">Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}">Login</a> | <a href="{{ route('register') }}">Register</a>
        @endauth
        <div class="position-relative">
            <a href="keranjang" class="text-white">
                <i class="fas fa-shopping-cart fa-2x"></i>
                <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
            </a>
        </div>
    </div>

    </div>
</nav>


<br></br><br></br>
<div class="container mt-3">
    <div class="d-flex justify-content-center flex-wrap gap-4">
        <a href="#bahan_pokok" class="btn btn-outline-success px-4 py-2">Bahan Pokok</a>
        <a href="#snack" class="btn btn-outline-success px-4 py-2">Snack</a>
        <a href="#minuman" class="btn btn-outline-success px-4 py-2">Minuman</a>
        <a href="#obat" class="btn btn-outline-success px-4 py-2">Obat</a>
        <a href="#alat_mandi" class="btn btn-outline-success px-4 py-2">Alat Mandi</a>
    </div>
</div>

<br></br>

<!-- Carousel -->
<div id="carouselExampleIndicators" class="carousel slide container mt-3" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/images/kooo.png" class="d-block w-100 carousel-img" alt="Promo 1">
        </div>
        <div class="carousel-item">
            <img src="/images/semba.png" class="d-block w-100 carousel-img" alt="Promo 2">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- CSS untuk memastikan gambar tidak terpotong -->
<style>
    .carousel-img {
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: contain;
        /* Gambar ditampilkan utuh tanpa terpotong */
    }
</style>

<div class="container mt-3 text-center">
    <h2 class="fw-bold">Produk yang mungkin Anda suka</h2>
</div>
<br>


<!-- daftar produk -->
<div class="row justify-content-center">
    <div class="col-md-9 mx-auto">
        <div id="bahan_pokok" class="border-bottom">
            <h3>Bahan Pokok</h3>
        </div>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($dataproduk->where('kategori_id', 1) as $data)
            <div class="col">
                <div class="card shadow-sm p-2 text-center">
                    <div class="card-body">
                        <img src="{{ asset($data->foto) }}"
                            class="card-img-top mb-2"
                            alt="{{ $data->nama_produk }}"
                            style="height: 150px; object-fit: cover; width: 100%;">
                        <h6 class="fw-bold">{{ $data->nama_produk }} ({{$data->stok}})</h6>
                        <p class="card-text">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success add-to-cart" data-id="{{ $data->id }}" data-stok="{{ $data->stok }}" data-name="{{ $data->nama_produk }}" data-price="{{ $data->harga }}">Tambah</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div> <br><br>

        <div id="snack" class="border-bottom">
            <h3>Snack</h3>
        </div>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($dataproduk->where('kategori_id', 3) as $data)
            <div class="col">
                <div class="card shadow-sm p-2 text-center">
                    <div class="card-body">
                        <img src="{{ asset($data->foto) }}"
                            class="card-img-top mb-2"
                            alt="{{ $data->nama_produk }}"
                            style="height: 150px; object-fit: cover; width: 100%;">
                        <h6 class="fw-bold">{{ $data->nama_produk }} ({{$data->stok}})</h6>
                        <p class="card-text">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success add-to-cart" data-id="{{ $data->id }}" data-stok="{{ $data->stok }}" data-name="{{ $data->nama_produk }}" data-price="{{ $data->harga }}">Tambah</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div> <br><br>

        <div id="minuman" class="border-bottom">
            <h3>Minuman</h3>
        </div>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($dataproduk->where('kategori_id', 5) as $data)
            <div class="col">
                <div class="card shadow-sm p-2 text-center">
                    <div class="card-body">
                        <img src="{{ asset($data->foto) }}"
                            class="card-img-top mb-2"
                            alt="{{ $data->nama_produk }}"
                            style="height: 150px; object-fit: cover; width: 100%;">
                        <h6 class="fw-bold">{{ $data->nama_produk }} ({{$data->stok}})</h6>
                        <p class="card-text">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success add-to-cart" data-id="{{ $data->id }}" data-stok="{{ $data->stok }}" data-name="{{ $data->nama_produk }}" data-price="{{ $data->harga }}">Tambah</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div> <br><br>

        <div id="obat" class="border-bottom">
            <h3>Obat</h3>
        </div>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($dataproduk->where('kategori_id', 6) as $data)
            <div class="col">
                <div class="card shadow-sm p-2 text-center">
                    <div class="card-body">
                        <img src="{{ asset($data->foto) }}"
                            class="card-img-top mb-2"
                            alt="{{ $data->nama_produk }}"
                            style="height: 150px; object-fit: cover; width: 100%;">
                        <h6 class="fw-bold">{{ $data->nama_produk }} ({{$data->stok}})</h6>
                        <p class="card-text">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success add-to-cart" data-id="{{ $data->id }}" data-stok="{{ $data->stok }}" data-name="{{ $data->nama_produk }}" data-price="{{ $data->harga }}">Tambah</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div> <br><br>

        <div id="alat_mandi" class="border-bottom">
            <h3>Alat Mandi</h3>
        </div>
        <br>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($dataproduk->where('kategori_id', 7) as $data)
            <div class="col">
                <div class="card shadow-sm p-2 text-center">
                    <div class="card-body">
                        <img src="{{ asset($data->foto) }}"
                            class="card-img-top mb-2"
                            alt="{{ $data->nama_produk }}"
                            style="height: 150px; object-fit: cover; width: 100%;">
                        <h6 class="fw-bold">{{ $data->nama_produk }} ({{$data->stok}})</h6>
                        <p class="card-text">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-success add-to-cart" data-id="{{ $data->id }}" data-stok="{{ $data->stok }}" data-name="{{ $data->nama_produk }}" data-price="{{ $data->harga }}">Tambah</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<!-- footer -->
<br><br>
<footer style="background-color: #111; color: #eee; padding: 40px 0; font-family: Arial, sans-serif;">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Kolom Kiri -->
            <div class="col-md-4 mb-4">
                <h4 style="font-weight: bold; color: #ffc107;">TOKO SEMBAKO</h4>
                <p style="font-size: 15px;">
                    Sebagai Pusat Sembako Online, kami menghadirkan pilihan kebutuhan sehari-hari dengan memperluas jangkauan produk,
                    mulai dari harga terjangkau. Kami menjadikan anda sebagai prioritas utama.
                    <strong style="color: #ffc107;">Bersama TOKO SEMBAKO, Belanja Mudah, Hidup Nyaman.</strong>
                </p>
            </div>

            <!-- Kolom Tengah -->
            <div class="col-md-4 mb-4">
                <h4 style="font-weight: bold; color: #ffc107;">Layanan Pengaduan Konsumen</h6>
                    <p style="margin: 5px 0;">Toko Sembako</p>
                    <p style="margin: 0;">
                        <a href="mailto:adlidwi" style="text-decoration: none; color: #ffc107;">
                            <i class="fas fa-envelope" style="margin-right: 6px;"></i>: customer@TokoSembako.com
                        </a>
                    </p>

                    <p style="margin-top: 5px;">
                        <a href="https://wa.me/6281332795308" target="_blank" style="text-decoration: none; color: #25d366;">
                            <i class="fab fa-whatsapp" style="margin-right: 6px;"></i>: +62 853 1111 1010
                        </a>
                    </p>


            </div>

            <!-- Kolom Kanan (Foto Pemilik) -->
            <div class="col-md-4 mb-4 text-center">
                <h4 style="font-weight: bold; color: #ffc107;">Pemilik Toko</h4>
                <div style="margin-top: 5px;"><a href="https://www.instagram.com/aldydwirwn/?utm_source=ig_web_button_share_sheet" target="_blank">
                    <img src="{{ asset('images/aldiCEO.jpg') }}" alt="Foto Pemilik"
                        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #ffc107; box-shadow: 0 0 10px rgba(255, 193, 7, 0.7);">
                </a>
                    <p style="margin-top: 10px; font-size: 14px;"><strong>Aldi Dwi Irawawn</strong></p>
                </div>
            </div>
            <p style="margin-top: 10px; font-size: 14px; text-align: center;">
                © 2020-2025 TOKO SEMBAKO |
                <a href="#" style="color: #eee; text-decoration: underline;">Kebijakan Privasi</a> |
                <a href="#" style="color: #eee; text-decoration: underline;">Persyaratan dan Ketentuan</a>
            </p>
        </div>
    </div>
</footer>

<!-- FontAwesome untuk ikon -->
<script src="https://kit.fontawesome.com/YOUR-FONT-AWESOME-KIT.js" crossorigin="anonymous"></script>



</div>
</div>

<!-- Notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let cart = [];
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let productImg = this.getAttribute('data-img');
            let productName = this.getAttribute('data-name');
            let productPrice = parseInt(this.getAttribute('data-price'));
            let productStok = parseInt(this.getAttribute('data-stok'));

            let existingProduct = cart.find(item => item.id === productId);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    stok: productStok,
                    quantity: 1
                });
            }

            updateCartCount();
            localStorage.setItem('cart', JSON.stringify(cart));

            Swal.fire({
                title: "Berhasil!",
                text: `Produk "${productName}" telah ditambahkan ke keranjang.`,
                icon: "success",
                showConfirmButton: false,
                timer: 2000
            })
        });
    });

    function updateCartCount() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.quantity, 0);
    }

    window.onload = function() {
        cart = JSON.parse(localStorage.getItem('cart')) || [];
        updateCartCount();
    };

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault(); // Menghindari navigasi default
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');
    const categories = document.querySelectorAll('#bahan_pokok, #snack, #minuman, #obat, #alat_mandi');

    if (query) { // Jika q tidak kosong
        categories.forEach(category => {
            category.style.display = 'none';
        });
    }
</script>

@endsection