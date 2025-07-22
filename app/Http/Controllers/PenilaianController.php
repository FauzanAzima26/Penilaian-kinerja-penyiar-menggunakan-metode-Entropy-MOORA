<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
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
            'nilai.*' => 'required|numeric|min:0'
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
    public function update(Request $request)
    {
        $data = Penilaian::updateOrCreate(
            [
                'id_penyiar' => $request->id_penyiar,
                'id_kriteria' => $request->id_kriteria,
            ],
            [
                'nilai' => $request->nilai,
                'id_evaluator' => auth()->id() ?? 1
            ]
        );

        return response()->json(['message' => 'Berhasil disimpan']);
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
    $id_penyiar = $request->id_penyiar;

    // Ambil semua kriteria
    $kriteria = Kriteria::all();

    // Ambil penilaian untuk penyiar yang dipilih
    $penilaian = Penilaian::where('id_penyiar', $id_penyiar)->get();

    // Gabungkan nilai ke setiap kriteria (default 0 jika belum dinilai)
    $data = $kriteria->map(function ($k) use ($penilaian) {
        $nilai = $penilaian->firstWhere('id_kriteria', $k->id);
        return [
            'id_kriteria' => $k->id,
            'kriteria' => $k->nama,
            'nilai' => $nilai ? $nilai->nilai : 0
        ];
    });

    return response()->json(['data' => $data]);
}

// Tambahan method updateNilai
public function updateNilai(Request $request)
{
    $request->validate([
        'id_penyiar' => 'required|exists:penyiar,id',
        'id_kriteria' => 'required|exists:kriteria,id',
        'nilai' => 'required|numeric|min:0'
    ]);

    $data = Penilaian::updateOrCreate(
        [
            'id_penyiar' => $request->id_penyiar,
            'id_kriteria' => $request->id_kriteria,
        ],
        [
            'nilai' => $request->nilai,
            'id_evaluator' => auth()->id() ?? 1,
            'updated_at' => now()
        ]
    );

    return response()->json(['message' => 'Berhasil disimpan']);
}

}
