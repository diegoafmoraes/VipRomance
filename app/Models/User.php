<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Photo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'city',
        'state',
        'hair_color',
        'eye_color',
        'height_cm',
        'weight_kg',
        'body_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Criar um scope no User
     *
     * @param Builder $query
     * @param self $me
     * @return Builder
     */
    public function scopeReciprocalMatches(Builder $q, User $me): Builder
    {
        return $q->where('sex', $me->seeking)
            ->where('seeking', $me->sex);
    }

    /**
     * Relacionamento de dados: Tratar upload de Fotos Album
     *
     * @return void
     */
    public function photos()
    {
        return $this->hasMany(\App\Models\Photo::class, 'user_id', 'id');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(Photo::class)
            ->where('is_primary', 1);
    }

    /**
     * Relacionamento de dados: Tratar upload de Fotos Perfil
     *
     * @return void
     */
    public function mainPhoto()
    {
        return $this->hasOne(Photo::class)
            ->where('is_primary', 1);
    }

    /**
     * Relacionamento de tabelas
     *
     * @return void
     */
    public function preferences()
    {
        return $this->hasMany(\App\Models\UserPreference::class, 'user_id', 'id');
    }
}
