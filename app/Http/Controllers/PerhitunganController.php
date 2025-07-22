<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    private function hitungEntropy($penilaian)
    {
        $data = [];

        // Group nilai berdasarkan id_kriteria dan id_penyiar
        foreach ($penilaian as $row) {
            $data[$row->id_kriteria][$row->id_penyiar][] = $row->nilai;
        }

        $result = [];

        foreach ($data as $id_kriteria => $penyiar_nilai) {
            $avg_nilai = [];
            $total = 0;

            // Hitung rata-rata nilai penyiar dan total nilai
            foreach ($penyiar_nilai as $id_penyiar => $nilai_array) {
                $avg = array_sum($nilai_array) / count($nilai_array);
                $avg_nilai[$id_penyiar] = $avg;
                $total += $avg;
            }

            $entropy = 0;
            foreach ($avg_nilai as $nilai) {
                if ($nilai <= 0 || $total == 0) continue;
                $p = $nilai / $total;
                $entropy -= $p * log($p, 2);
            }

            // Normalisasi entropy
            $jumlah_penyiar = count($avg_nilai);
            if ($jumlah_penyiar > 1 && log($jumlah_penyiar, 2) > 0) {
                $e = 1 / log($jumlah_penyiar, 2);
                $entropy *= $e;

                $entropy = min($entropy, 1.0);
                $degree_of_divergence = 1 - $entropy;

                $result[] = [
                    'id_kriteria' => $id_kriteria,
                    'entropy' => round($entropy, 6),
                    'bobot' => $degree_of_divergence, // Jangan dibulatkan dulu
                ];
            } else {
                $result[] = [
                    'id_kriteria' => $id_kriteria,
                    'entropy' => 0,
                    'bobot' => 0,
                ];
            }
        }

        // Normalisasi bobot agar total = 1
        $total_bobot = array_sum(array_column($result, 'bobot'));
        if ($total_bobot > 0) {
            $total_normalisasi = 0;
            foreach ($result as $i => &$r) {
                $r['bobot'] = round($r['bobot'] / $total_bobot, 6);
                $total_normalisasi += $r['bobot'];
            }
            unset($r); // Hindari bug karena reference

            // Koreksi pembulatan ke elemen terakhir
            $selisih = 1.0 - $total_normalisasi;
            $result[count($result) - 1]['bobot'] += $selisih;
            $result[count($result) - 1]['bobot'] = round($result[count($result) - 1]['bobot'], 6);
        }

        // Hitung ulang total bobot setelah pembulatan dan koreksi
        $final_total = array_sum(array_column($result, 'bobot'));

        return $result;
    }

    private function hitungMoora($penilaian, $entropy)
    {
        $bobot = [];
        foreach ($entropy as $e) {
            $bobot[$e['id_kriteria']] = $e['bobot'];
        }

        // Susun matriks nilai
        $matrix = [];
        foreach ($penilaian as $row) {
            $matrix[$row->id_penyiar][$row->id_kriteria][] = $row->nilai;
        }

        // Hitung rata-rata
        foreach ($matrix as $id_penyiar => &$nilai_kriteria) {
            foreach ($nilai_kriteria as $id_kriteria => &$nilai_array) {
                $nilai_array = array_sum($nilai_array) / count($nilai_array);
            }
        }

        // Normalisasi matriks
        $normalisasi = [];
        foreach ($bobot as $id_kriteria => $w) {
            $pembagi = 0;
            foreach ($matrix as $penyiar => $kriteria_nilai) {
                $pembagi += pow($kriteria_nilai[$id_kriteria] ?? 0, 2);
            }
            $pembagi = sqrt($pembagi);

            foreach ($matrix as $penyiar => $kriteria_nilai) {
                $nilai = $kriteria_nilai[$id_kriteria] ?? 0;
                $normalisasi[$penyiar][$id_kriteria] = ($pembagi != 0) ? $nilai / $pembagi : 0;
            }
        }

        // Hitung nilai akhir
        $hasil = [];
        foreach ($normalisasi as $penyiar => $nilai_kriteria) {
            $nilai_akhir = 0;
            foreach ($nilai_kriteria as $id_kriteria => $nilai) {
                $nilai_akhir += $nilai * $bobot[$id_kriteria];
            }
            $hasil[] = [
                'id_penyiar' => $penyiar,
                'nilai_akhir' => round($nilai_akhir, 6),
            ];
        }

        // Ranking
        usort($hasil, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);
        foreach ($hasil as $i => &$row) {
            $row['ranking'] = $i + 1;
        }

        return $hasil;
    }

    public function hitung()
    {
        $penilaian = DB::table('penilaian')->get();

        $entropy = $this->hitungEntropy($penilaian);

        foreach ($entropy as $item) {
            DB::table('entropy')->updateOrInsert(
                ['id_kriteria' => $item['id_kriteria']],
                [
                    'entropy' => $item['entropy'],
                    'bobot' => $item['bobot'],
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }

        $moora = $this->hitungMoora($penilaian, $entropy);

        foreach ($moora as $item) {
            DB::table('moora')->updateOrInsert(
                ['id_penyiar' => $item['id_penyiar']],
                [
                    'nilai_akhir' => $item['nilai_akhir'],
                    'ranking' => $item['ranking'],
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }

        return redirect()->back()->with('success', 'Perhitungan berhasil dilakukan.');
    }

    public function getData()
    {
        $data = DB::table('moora')
            ->join('penyiar', 'moora.id_penyiar', '=', 'penyiar.id') // join agar bisa ambil nama
            ->select('moora.id_penyiar', 'penyiar.nama', 'moora.nilai_akhir', 'moora.ranking')
            ->orderBy('moora.ranking', 'asc')
            ->get();

        return response()->json(['data' => $data]);
    }
}
