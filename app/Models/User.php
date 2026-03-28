<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Photo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos liberados para mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'sex',
        'seeking',
        'bio',
        'city',
        'state',
        'hair_color',
        'eye_color',
        'height_cm',
        'weight_kg',
        'body_type',
        'marital_status',
    ];

    /**
     * Campos ocultos
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope de compatibilidade recíproca
     */
    public function scopeReciprocalMatches(Builder $q, User $me): Builder
    {
        return $q->where('sex', $me->seeking)
            ->where('seeking', $me->sex);
    }

    /**
     * Álbum de fotos
     */
    public function photos()
    {
        return $this->hasMany(\App\Models\Photo::class, 'user_id', 'id');
    }

    /**
     * Foto principal
     */
    public function primaryPhoto()
    {
        return $this->hasOne(Photo::class, 'user_id', 'id')
            ->where('is_primary', 1);
    }

    /**
     * Alias para foto principal
     */
    public function mainPhoto()
    {
        return $this->hasOne(Photo::class, 'user_id', 'id')
            ->where('is_primary', 1);
    }

    /**
     * Preferências do usuário
     */
    public function preferences()
    {
        return $this->hasMany(\App\Models\UserPreference::class, 'user_id', 'id');
    }
}
