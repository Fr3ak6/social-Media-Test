<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'value', 'post_id', 'comment_id', 'votable_id', 'votable_type'];

    /**
     * La relazione polimorfica con il modello votato (Post o Comment)
     */
    public function votable()
    {
        return $this->morphTo();
    }

    /**
     * La relazione con l'utente che ha votato
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
