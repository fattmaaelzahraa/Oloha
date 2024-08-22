<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $appends = ['experience_photo_url'];


    public function getExperiencePhotoUrlAttribute(): string
    {
//        return $this->place_photo ? url('images/images/' . $this->place_photo) : '';
        return url('storage/' . $this->experience_photo);
    }
}
