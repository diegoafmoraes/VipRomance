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
    ];

    /**
     * Criar um scope no User
     *
     * @param Builder $query
     * @param self $me
     * @return Builder
     */
    public function scopeReciprocalMatches(Builder $query, self $me): Builder
    {
        return $query
            ->where('id', '!=', $me->id)
            ->where('is_active', 1)
            ->where('sex', $me->seeking)     // o sexo do outro tem que ser o que eu procuro
            ->where('seeking', $me->sex);    // e o outro tem que procurar o meu sexo
    }

    /**
     * Relacionamento de dados: Tratar upload de Fotos Album
     *
     * @return void
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
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
}
