<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'opening_time','closing_time', 'waiting_time', 'place_photo','about', 'type', 'capacity', 'good_for', 'privileges', 'vibes'];
    protected $appends = ['place_photo_url'];


    public function getPlacePhotoUrlAttribute(): string
    {
//        return $this->place_photo ? url('images/images/' . $this->place_photo) : '';
        return url('storage/' . $this->place_photo);
    }


    public function usersVisits(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'visits');
    }

}
