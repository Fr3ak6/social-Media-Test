<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id'
        ]);

        Comment::create([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
            'post_id' => $request->input('post_id'),
        ]);

        return redirect()->back()->with('success', 'Commento aggiunto con successo.');
    }
}
