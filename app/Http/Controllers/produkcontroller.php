<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\produk;
use App\Models\kategori;
use App\Models\HistoriOpname;
use App\Models\StokMasuk;
use App\Models\StokHistori;
use App\Models\StokKeluar;
use App\Models\StokOpname;

class produkcontroller extends Controller
{
    public function index()
    {
        $data = produk::all();
        return view('dashboard_admin.Produk.index', ['dataproduk' => $data]);
    }
    public function order()
    {
        $dataproduk = produk::all();
        return view('order', compact('dataproduk'));
    }
    public function create()
    {
        $kategori = kategori::all();
        return view('dashboard_admin.Produk.create', compact('kategori'));
    }
    public function store(Request $request)
    {
        $data = new produk();
        $data->id = $request->id;
        $data->nama_produk = $request->nama_produk;
        $data->kategori_id = $request->kategori;
        $data->harga = $request->harga;
        $data->stok = $request->stok;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'images/' . $filename;
            $file->move(public_path('images'), $filename);
            $data->foto = $path;
        }

        $data->save();
        return redirect('/tampil-produk');
    }
    public function edit($id)
    {
        $data = produk::find($id);
        $kategoris = kategori::all();
        return view('dashboard_admin.Produk.edit', ['data' => $data, 'kat' => $kategoris]);
    }
    public function update(Request $request, $id)
    {
        $data = produk::find($id);
        $data->nama_produk = $request->nama_produk;
        $data->kategori_id = $request->kategori;
        $data->harga = $request->harga;

        if ($request->hasFile('foto')) {
            if ($data->foto && file_exists(public_path($data->foto))) {
                unlink(public_path($data->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'images/' . $filename;
            $file->move(public_path('images'), $filename);
            $data->foto = $path;
        }

        $data->update();
        return redirect('/tampil-produk');
    }
    public function destroy($id)
    {
        $data = produk::find($id);
        $data->delete();
        return redirect('/tampil-produk');
    }

    public function indexStokMasuk()
    {
        $historiStokMasuk = StokHistori::all();
        $StokMasuk = StokMasuk::all();

        return view('dashboard_admin.Produk.stokMasuk', compact('historiStokMasuk', 'StokMasuk'));
    }

    public function indexStokKeluar()
    {
        $historiStokKeluar = StokHistori::all();
        $StokKeluar = StokKeluar::all();

        return view('dashboard_admin.Produk.stokKeluar', compact('historiStokKeluar', 'StokKeluar'));
    }

    public function indexStokOpname()
    {
        $historiStokOpname = HistoriOpname::all();
        $StokOpname = StokOpname::all();

        return view('dashboard_admin.Produk.stokOpname', compact('historiStokOpname', 'StokOpname'));
    }

    public function indexStokMasukForm()
    {
        return view('dashboard_admin.Produk.formStok');
    }

    public function indexStokKeluarForm()
    {
        return view('dashboard_admin.Produk.formStokKeluar');
    }

    public function indexStokOpnameForm()
    {
        $produkList = produk::all();
        return view('dashboard_admin.Produk.formStokOpname', compact('produkList'));
    }

    public function stokMasukSearch(Request $request)
    {
        $query = $request->get('query');

        $produk = Produk::where('nama_produk', 'like', "%{$query}%")->get();

        $output = '';
        foreach ($produk as $item) {
            $output .= '<a href="#" class="list-group-item list-group-item-action produk-item" data-id="' . $item->id . '">' . $item->nama_produk . '</a>';
        }

        return response()->json($output);
    }

    public function stokMasukStore(Request $request)
    {

        $validated = $request->validate([
            'produk_id'  => 'required|array',
            'harga'      => 'required|array',
            'qty'        => 'required|array',
            'created_at'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $histori = StokHistori::create([
            'keterangan' => $validated['keterangan'] ?? 'TIdak ada keterangan',
            'tipe' => 'Stok Masuk',
            'created_at' => $validated['created_at'],
        ]);

        for ($i = 0; $i < count($validated['produk_id']); $i++) {
            $produkId = $validated['produk_id'][$i];
            $qty      = $validated['qty'][$i];
            $harga      = $validated['harga'][$i];
            $total = $qty * $harga;

            StokMasuk::create([
                'id_histori' => $histori->id,
                'id_produk'  => $produkId,
                'qty'     => $qty,
                'harga'     => $harga,
                'total' => $total,
                'created_at'    => $validated['created_at'],
            ]);

            // Update stok produk
            $produk = Produk::find($produkId);
            if ($produk) {
                $produk->stok += $qty;
                $produk->save();
            }
        }

        return redirect()->back()->with('success', 'Stok masuk berhasil disimpan!');
    }

    public function stokKeluarStore(Request $request)
    {

        $validated = $request->validate([
            'produk_id'  => 'required|array',
            'qty'        => 'required|array',
            'created_at'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $histori = StokHistori::create([
            'keterangan' => $validated['keterangan'] ?? 'TIdak ada keterangan',
            'tipe' => 'Stok Keluar',
            'created_at' => $validated['created_at'],
        ]);

        for ($i = 0; $i < count($validated['produk_id']); $i++) {
            $produkId = $validated['produk_id'][$i];
            $qty      = $validated['qty'][$i];

            StokKeluar::create([
                'id_histori' => $histori->id,
                'id_produk'  => $produkId,
                'qty'     => $qty,
                'created_at'    => $validated['created_at'],
            ]);

            // Update stok produk
            $produk = Produk::find($produkId);
            if ($produk) {
                $produk->stok -= $qty;
                $produk->save();
            }
        }

        return redirect()->back()->with('success', 'Stok keluar berhasil disimpan!');
    }

    public function stokOpnameStore(Request $request)
    {

        $validated = $request->validate([
            'produk_id' => 'required|array',
            'stok_fisik' => 'required|array',
            'created_at' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $histori = HistoriOpname::create([
            'keterangan' => $validated['keterangan'] ?? 'TIdak ada keterangan',
            'created_at' => $validated['created_at'],
        ]);

        foreach ($validated['produk_id'] as $produkId) {
            $produk = produk::find($produkId);
            $stokSistem = $produk ? $produk->stok : 0;
            $stokFisik = isset($validated['stok_fisik'][$produkId]) ? (int) $validated['stok_fisik'][$produkId] : 0;
            $selisih = $stokSistem - $stokFisik;

            StokOpname::create([
                'id_histori'   => $histori->id,
                'id_produk'    => $produkId,
                'stok_sistem'  => $stokSistem,
                'stok_fisik'   => $stokFisik,
                'selisih'      => $selisih,
                'created_at'   => $validated['created_at'],
            ]);
        }

        return redirect()->back()->with('success', 'Stok opname berhasil disimpan!');
    }
}
