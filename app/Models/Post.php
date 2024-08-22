<?php

namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

class Post extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected $appends = ['post_photo_url'];

    public function post_photo_url(): Application|string|UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return url('storage/' . $this->post_photo_url());
    }
}
