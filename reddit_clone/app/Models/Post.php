<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['title', 'content', 'user_id', 'channel_id', 'image_path'];



    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function votes() {return $this->morphMany(Vote::class, 'votable');}
    public function tags() { return $this->belongsToMany(Tag::class); }
    public function channel() { return $this->belongsTo(Channel::class); }

    public function getVotesSumAttribute() { return $this->votes()->sum('value'); }
    

}
