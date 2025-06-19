<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class user_control extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth::user();
        $dataTransaksi = Transaction::where('user_id', $user->id)->with('details')->get();

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:6',
                'alamat' => 'required|string|max:255',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->alamat = $request->alamat;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = 'user_' . $user->id . '.' . $file->getClientOriginalExtension();
                $path = 'images/' . $filename;
            
                // Hapus foto lama jika ada
                if ($user->foto && file_exists(public_path($user->foto))) {
                    unlink(public_path($user->foto));
                }
            
                // Pindahkan file ke folder public/images
                $file->move(public_path('images'), $filename);
            
                // Simpan path relatif ke database
                $user->foto = $path;
            }
            
            $user->save();

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        }

        return view('profile', compact('user', 'dataTransaksi'));
    }
    public function index(){
        $data = User::all();
        return view('dashboard_admin.User.index',['dataUser'=> $data]);
    }
    public function login(Request $request)
    {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        return redirect()->intended('/dashboard'); // Redirect jika sukses
    }

    return back()->withErrors(['login' => 'Email atau password salah!'])->withInput();
}

}
