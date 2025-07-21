<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penyiar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PenyiarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penyiar.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Buat akun user untuk penyiar
        $password = '00000000'; // password acak
        $email = Str::slug($request->nama) . '@penyiar.local'; // generate email unik (bisa disesuaikan)

        $user = User::create([
            'name' => $request->nama,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'penyiar',
        ]);

        // Buat penyiar dan kaitkan ke user
        Penyiar::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'id_user' => $user->id
        ]);

        return response()->json([
            'message' => 'Data penyiar & user berhasil dibuat.',
            'generated_email' => $email,
            'generated_password' => $password,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penyiar = Penyiar::findOrFail($id);
        return response()->json($penyiar);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $penyiar = Penyiar::findOrFail($id);
        $penyiar->update([
            'nama' => $request->nama,
        ]);

        // Update nama dan email pada user terkait
        $user = $penyiar->user; // menggunakan relasi
        if ($user) {
            $user->update([
                'name' => $request->nama,
                'email' => Str::slug($request->nama) . '@penyiar.local',
            ]);
        }

        return response()->json(['message' => 'Data berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penyiar = Penyiar::findOrFail($id);
        $penyiar->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function getData(Request $request)
    {
        $data = Penyiar::select('penyiar.id', 'penyiar.nama', 'users.email')
            ->join('users', 'users.id', '=', 'penyiar.id_user')
            ->get();

        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json']);
    }
}
