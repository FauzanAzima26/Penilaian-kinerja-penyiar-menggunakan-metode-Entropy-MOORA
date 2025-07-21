<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyiar = DB::table('penyiar')->get();
        $kriteria = DB::table('kriteria')->get();
        return view('penilaian.index', compact('penyiar', 'kriteria'));
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
            'id_penyiar' => 'required|exists:penyiar,id',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0'
        ]);

        $id_evaluator = Auth::id();

        foreach ($request->nilai as $id_kriteria => $nilai) {
            DB::table('penilaian')->insert([
                'id_penyiar' => $request->id_penyiar,
                'id_kriteria' => $id_kriteria,
                'id_evaluator' => $id_evaluator,
                'nilai' => $nilai,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['status' => 'success']);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getData(Request $request)
    {
        $data = DB::table('penilaian')
            ->join('kriteria', 'kriteria.id', '=', 'penilaian.id_kriteria')
            ->join('users', 'users.id', '=', 'penilaian.id_evaluator')
            ->where('penilaian.id_penyiar', $request->id_penyiar)
            ->select([
                'kriteria.nama as kriteria',
                'penilaian.nilai',
                'users.name as evaluator',
                'penilaian.created_at'
            ])
            ->get();

        return response()->json(['data' => $data]);
    }
}
