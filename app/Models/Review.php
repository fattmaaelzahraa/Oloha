<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;
    protected $guarded = [];


    protected $appends = ['photo_path_url'];


    public function getReviewPhotoUrlAttribute(): string
    {
//        return $this->place_photo ? url('images/images/' . $this->place_photo) : '';
        return url('storage/' . $this->photo_path);
    }

    public function user_review(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function place_review(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
