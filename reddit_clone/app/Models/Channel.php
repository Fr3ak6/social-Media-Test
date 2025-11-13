<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function users() { return $this->belongsToMany(User::class); }
    public function posts() { return $this->hasMany(Post::class); }
}
