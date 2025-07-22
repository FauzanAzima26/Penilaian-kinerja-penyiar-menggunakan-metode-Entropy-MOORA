<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'id_penyiar',
        'id_kriteria',
        'nilai',
        'id_evaluator', // opsional kalau pakai evaluator login
    ];

    // Relasi ke model Penyiar
    public function penyiar()
    {
        return $this->belongsTo(Penyiar::class, 'id_penyiar');
    }

    // Relasi ke model Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    // Jika evaluator adalah user
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'id_evaluator');
    }

    public $timestamps = true;

}
