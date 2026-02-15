<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'path',
        'is_primary',
        'is_private',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_private' => 'boolean',
    ];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->path, '/'));
    }    
    
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
