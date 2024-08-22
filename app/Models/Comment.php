<?php

namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

class Comment extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $appends = ['comment_photo_url'];
    public function comment_photo_url(): Application|string|UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return url('storage/' . $this->comment_photo_url());
    }
}
