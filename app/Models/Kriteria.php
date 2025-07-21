<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

     // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama',
        'tipe',
    ];

    public $timestamps = true;
}
