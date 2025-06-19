<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user_control;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\produkcontroller;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// CUSTOMER ====================================================================
Route::get('/profile', function () {
    return view('profile');
});
Route::get('/histori', [TransaksiController::class, 'index']);


Route::match(['get', 'post'], '/profile', [user_control::class, 'profile'])
    ->middleware('auth')
    ->name('profile');

Route::get('/order', [ProdukController::class, 'order']);

Route::get('/keranjang', function () {
    return view('keranjang');
});

// search ---
Route::get('/search', [SearchController::class, 'search'])->name('search');




// ADMIN =========================================================================
Route::get('/admin', function () {
    return view('dashboard_admin.admin');
});




//middleware / produk -------------
Route::middleware(['auth','admin'])->group(function () {
    Route::get('tampil-produk', [produkcontroller::class, 'index']);
    //Route::get('/tambah-produk', [produkcontroller::class])
});
Route::get('tambah-produk', [produkcontroller::class, 'create'])->name('produk.create');
Route::post('tampil-produk', [produkcontroller::class, 'store'])->name('produk.store');
Route::get('/produk/edit/{id}', [produkcontroller::class, 'edit'])->name('produk.edit');
Route::post('/produk/edit/{id}', [produkcontroller::class, 'update'])->name('produk.update');
Route::post('/produk/delete/{id}', [produkcontroller::class, 'destroy'])->name('produk.delete');

Route::get('/produk/opname', [produkcontroller::class, 'opname'])->name('produk.opname');
Route::get('/produk/histori-opname', [produkcontroller::class, 'historiOpname'])->name('produk.opname.histori');
Route::post('/produk/opname/update', [produkcontroller::class, 'updateStokOpname'])->name('produk.opname.update');

// stok masuk
Route::get('/stok-masuk', [produkcontroller::class, 'indexStokMasuk'])->name('stok-masuk');
Route::get('/stok-masuk/form', [produkcontroller::class, 'indexStokMasukForm'])->name('stok-masuk.form');
Route::post('/stok-masuk/store', [produkcontroller::class, 'stokMasukStore'])->name('stok-masuk.store');

Route::get('/produk/search', [produkcontroller::class, 'stokMasukSearch'])->name('produk.search');

//stok keluar
Route::get('/stok-keluar', [produkcontroller::class, 'indexStokKeluar'])->name('stok-keluar');
Route::get('/stok-keluar/form', [produkcontroller::class, 'indexStokKeluarForm'])->name('stok-keluar.form');
Route::post('/stok-keluar/store', [produkcontroller::class, 'stokKeluarStore'])->name('stok-keluar.store');

//stok opname
Route::get('/stok-opname', [produkcontroller::class, 'indexStokOpname'])->name('stok-opname');
Route::get('/stok-opname/form', [produkcontroller::class, 'indexStokOpnameForm'])->name('stok-opname.form');
Route::post('/stok-opname/store', [produkcontroller::class, 'stokOpnameStore'])->name('stok-opname.store');




// kategori
Route::controller(kategoricontroller::class)->group(function(){
    Route::get('tampil-kategori','index');
    Route::get('tambah-kategori','create')->name('kategori.create');
    Route::post('tampil-kategori','store')->name('kategori.store');
    Route::get('/kategori/edit/{id}','edit')->name('kategori.edit');
    Route::post('/kategori/edit/{id}','update')->name('kategori.update');
    Route::post('/kategori/delete/{id}','destroy')->name('kategori.delete');
});

// transaksi
Route::get('/tampil-transaksi', [TransaksiController::class, 'indexAdmin']);
Route::post('/transaksi/sampai/{id}', [TransaksiController::class, 'sampai']);


// admin -> user
Route::get('/tampil-User', [user_control::class, 'index']);


// laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
Route::get('/laporan/cari', [LaporanController::class, 'cari'])->name('laporan.cari');
Route::get('/laporan/export/excel', [LaporanController::class, 'excel'])->name('laporan.excel');
Route::get('/laporan/export/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
Route::get('/laporan/chart', [LaporanController::class, 'chart'])->name('laporan.chart');

Route::get('/laporan/produk-terlaris', [LaporanController::class, 'produkTerlaris'])->name('laporan.produkTerlaris');

Route::get('/laporan/ongkir', [LaporanController::class, 'laporanOngkir'])->name('laporan.ongkir');
Route::get('/laporan/ongkir/export/excel', [LaporanController::class, 'excelOngkir'])->name('laporan.ongkir.excel');
Route::get('/laporan/ongkir/export/pdf', [LaporanController::class, 'pdfOngkir'])->name('laporan.ongkir.pdf');

// Midtrans
Route::post('/midtrans-token', [MidtransController::class, 'transaksi'])->name('midtrans.token');
Route::post('/update-status', [MidtransController::class, 'updateStatus'])->name('update.status');
Route::post('/update-status2', [MidtransController::class, 'updateStatus2'])->name('update.status2');