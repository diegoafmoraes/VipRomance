<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferenceOption extends Model
{
    protected $table = 'preference_options';

    protected $fillable = [
        'category',
        'key',
        'label',
        'sort_order',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}