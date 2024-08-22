<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'phonenumber',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function likedPlaces(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'visits')
            ->wherePivot('favourite', true);
    }

    public function likedGuides(): BelongsToMany
    {
        return $this->belongsToMany(Guide::class, 'feedbacks')
            ->wherePivot('like', true);
    }

    public function likedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'Attends')
            ->wherePivot('like', true);
    }
}
