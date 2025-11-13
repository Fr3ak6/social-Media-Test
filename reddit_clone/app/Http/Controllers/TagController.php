<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show($name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        $posts = $tag->posts()->with('user', 'votes', 'comments')->paginate(10);

        return view('tags.show', compact('tag', 'posts'));
    }
}
