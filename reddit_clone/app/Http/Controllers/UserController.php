<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{

    protected $fillable = [
        'id'
        
    ];

    public function show($id)
    {
        $user = User::with('posts.votes')->findOrFail($id);
        return view('users.show', compact('user'));
    }
}

