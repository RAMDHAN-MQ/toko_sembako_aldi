<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kategori;
class kategoricontroller extends Controller
{
    public function index(){
        $data = kategori::all();
        return view('dashboard_admin.Kategori.index',['datakategori'=> $data]);
    }

    public function create(){
        return view('dashboard_admin.Kategori.create');
    }

    public function store(Request $request){
        $data = new kategori();
        $data->id = $request->id;
        $data->nama_kategori = $request->nama_kategori;
        $data->save();
        return redirect('/tampil-kategori');
    }

    public function edit($id){
        $data = kategori::find($id);
        return view('dashboard_admin.Kategori.edit', compact('data'));
    }
    public function update(Request $request, $id){
        $data = kategori::find($id);
        $data->nama_kategori = $request->nama_kategori;
        $data->update();
        return redirect('/tampil-kategori');
    }
    public function destroy($id){
        $data = kategori::find($id);
        $data->delete();
        return redirect('/tampil-kategori');
    }
}
