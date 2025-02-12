<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }
}
