<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\produk;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;

class MidtransController extends Controller
{
    public function transaksi(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Pastikan user sudah login
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login untuk melakukan transaksi'], 401);
        }

        $user = Auth::user(); // Ambil data user yang login

        // Ambil data keranjang dari request
        $cart = $request->input('items');
        if (!$cart || count($cart) === 0) {
            return response()->json(['error' => 'Keranjang belanja kosong'], 400);
        }

        // Hitung total transaksi
        $totalHarga = array_reduce($cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        $ongkir = 16000;
        $totalHarga += $ongkir;

        $transactionDetails = [
            'order_id'     => 'ORDER-' . time(),
            'gross_amount' => $totalHarga,
        ];

        // Ambil data customer dari akun yang login
        $customerDetails = [
            'first_name' => $user->name,
            'email'      => $user->email,
        ];

        $itemDetails = array_map(function ($item) {
            return [
                'id'       => $item['id'],
                'price'    => $item['price'],
                'quantity' => $item['quantity'],
                'name'     => $item['name'],
            ];
        }, $cart);

        $itemDetails[] = [
            'id'       => '0', // ID khusus ongkir
            'price'    => $ongkir,
            'quantity' => 1,
            'name'     => 'Ongkos Kirim',
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'customer_details'    => $customerDetails,
            'item_details'        => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction);

            // Simpan transaksi ke dalam database

            $transaksi = Transaction::create([
                'user_id'    => $user->id,
                'harga'      => $totalHarga,
                'status'     => 'pending',
                'snap_token' => $snapToken,
            ]);


            // Simpan ke tabel `detail_transaksi`
            foreach ($cart as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $transaction = Transaction::where('id', $request->transaction_id)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        // Update status transaksi menjadi 'dikirim'
        $transaction->status = 'dikirim';
        $transaction->tanggal_bayar = now();
        $transaction->save();

        // Ambil detail transaksi dan kurangi stok produk
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaction->id)->get();
        foreach ($detailTransaksi as $detail) {
            $produk = produk::find($detail->produk_id);
            if ($produk) {
                $produk->stok -= $detail->jumlah;
                $produk->save();
            }
        }

        return response()->json(['message' => 'Status transaksi diperbarui'], 200);
    }

    public function updateStatus2(Request $request)
    {
        $transaction = Transaction::where('snap_token', $request->snap_token)->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        // Update status transaksi menjadi 'dikirim'
        $transaction->status = 'dikirim';
        $transaction->tanggal_bayar = now();
        $transaction->save();

        // Ambil detail transaksi dan kurangi stok produk
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaction->id)->get();
        foreach ($detailTransaksi as $detail) {
            $produk = produk::find($detail->produk_id);
            if ($produk) {
                $produk->stok -= $detail->jumlah;
                $produk->save();
            }
        }

        return response()->json(['message' => 'Status transaksi diperbarui'], 200);
    }
}
