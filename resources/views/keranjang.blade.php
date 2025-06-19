@extends('layouts.master')

@section('title', 'Keranjang')

@section('content')
<div class="container mt-4">
    <br>
    <h1 class="fw-bold">Keranjang Belanja</h1>
    <br><br>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="cart-items">
            <!-- Daftar produk akan di-generate dengan JavaScript -->
        </tbody>
    </table>
    <br><br><br>
    <div class="text-end">
        <h6>Ongkir: Rp <span id="shipping-cost">16.000</span></h5>
        <h6>Harga: Rp <span id="total-price">0</span></h4>
        <h4>Total: Rp <span id="grand-total">0</span></h4>
        <button class="btn btn-primary" id="checkout-btn">Bayar</button>
        <a href="order" class="btn btn-success">Batal</a>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let shippingCost = 16000;

    function renderCart() {
        let cartItemsContainer = document.getElementById('cart-items');
        let totalPrice = 0;
        cartItemsContainer.innerHTML = '';

        cart.forEach((item, index) => {
            let subtotal = item.price * item.quantity;
            totalPrice += subtotal;
            cartItemsContainer.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-end">Rp ${item.price.toLocaleString()}</td>
                    <td>
                        <div class="input-group" style="max-width: 150px;">
                            <button class="btn btn-sm btn-warning" onclick="decreaseQuantity(${index})">-</button>
                            <input type="number" class="form-control form-control-sm text-center" value="${item.quantity}" min="1" max="${item.stok}" onchange="changeQuantity(${index}, this.value)">
                            <button class="btn btn-sm btn-success" onclick="increaseQuantity(${index})">+</button>
                        </div>
                    </td>
                    <td class="text-end">Rp ${subtotal.toLocaleString()}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">Hapus</button>
                    </td>
                </tr>
            `;
        });

        let grandTotal = totalPrice + shippingCost;

        document.getElementById('total-price').innerText = totalPrice.toLocaleString();
        document.getElementById('shipping-cost').innerText = shippingCost.toLocaleString();
        document.getElementById('grand-total').innerText = grandTotal.toLocaleString();

    }

    function increaseQuantity(index) {
        if (cart[index].quantity < cart[index].stok) {
            cart[index].quantity++;
        } else {
            alert('Stok tidak mencukupi!');
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    function decreaseQuantity(index) {
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
        } else {
            cart.splice(index, 1);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    function changeQuantity(index, value) {
        let quantity = parseInt(value);
        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
        }
        if (quantity > cart[index].stok) {
            alert("Jumlah melebihi stok tersedia!");
            quantity = cart[index].stok;
        }
        cart[index].quantity = quantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }


    function removeItem(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    document.getElementById('checkout-btn').addEventListener('click', function() {
        let items = cart.map(item => ({
            id: item.id,
            price: item.price,
            quantity: item.quantity,
            name: item.name
        }));

        fetch("{{ route('midtrans.token') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        alert('Pembayaran berhasil!');
                        localStorage.removeItem('cart');
                        // Kirim request ke backend untuk update status transaksi ke "dikirim"
                        fetch("{{ route('update.status2') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    snap_token: data.snap_token
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log("Status transaksi diperbarui:", data);
                                window.location.href = '/order'; // Redirect setelah update
                            })
                            .catch(error => console.error("Error:", error));
                    },
                    onPending: function(result) {
                        alert('Menunggu pembayaran!');
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal!');
                    }
                });
            });
    });

    renderCart();
</script>
@endsection