<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [];
   protected $appends = ['event_photo_url'];
    public function getEventPhotoUrlAttribute(): string
    {
//        return $this->place_photo ? url('images/images/' . $this->place_photo) : '';
        return url('storage/' . $this->event_photo);
    }


}
