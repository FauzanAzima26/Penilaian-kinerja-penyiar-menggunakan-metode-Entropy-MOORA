<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyiar extends Model
{
    protected $table = 'penyiar';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama',
        'id_user'
    ];

    public $timestamps = true;

    // app/Models/Penyiar.php
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
