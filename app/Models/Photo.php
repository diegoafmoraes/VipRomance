<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * Metodo de relacionamento com fotos do usuario
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
