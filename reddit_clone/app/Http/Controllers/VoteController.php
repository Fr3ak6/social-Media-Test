<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{

    protected $fillable = [
        'user_id',
        'post_id',
        'votable_id',
        'votable_type',
        'value'
    ];
    
    public function vote(Request $request)
    {

        
        $request->validate([
            'type' => 'required|in:post,comment',
            'id' => 'required|integer',
            'value' => 'required|in:1,-1'
        ]);
    
        $user = auth()->user();
        $modelClass = $request->type === 'post' ? Post::class : Comment::class;
        $votable = $modelClass::findOrFail($request->id);
    
        // Rimuove eventuale voto precedente
        Vote::where([
            'user_id' => $user->id,
            'votable_id' => $votable->id,
            'votable_type' => $modelClass
        ])->delete();
    
        // Determina post_id (diretto o da commento)
        $postId = $request->type === 'post'
            ? $votable->id
            : $votable->post_id; // ← Assicurati che i commenti abbiano un campo post_id
    
        // Crea nuovo voto
        $votable->votes()->create([
            'user_id' => $user->id,
            'value' => $request->value,
            'post_id' => $postId
        ]);
        
    
        $totalVotes = $votable->votes()->sum('value');
    
        if ($request->expectsJson()) {
            return response()->json([
                'totalVotes' => $totalVotes,
            ]);
        }
    
        return back()->with('success', 'Voto registrato!');
    }
    
    

}

