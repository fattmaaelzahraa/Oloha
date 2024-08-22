<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;
    protected $guarded =[];

    protected $appends = ['guide_photo_url'];


    public function getGuidePhotoUrlAttribute(): string
    {
//        return $this->place_photo ? url('images/images/' . $this->place_photo) : '';
        return url('storage/' . $this->guide_photo);
    }
}
