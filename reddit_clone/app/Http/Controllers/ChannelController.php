<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use App\Models\Post;

class ChannelController extends Controller
{
    public function show($id)
    {
        $channel = Channel::with('posts')->findOrFail($id);
        return view('channels.show', compact('channel'));
    }

    public function join($id)
    {
        $channel = Channel::findOrFail($id);
        auth()->user()->channels()->attach($channel);
        return back()->with('success', 'Ti sei unito al canale!');
    }

    public function leave($id)
    {
        $channel = Channel::findOrFail($id);
        auth()->user()->channels()->detach($channel);
        return back()->with('success', 'Hai abbandonato il canale.');
    }

    public function index(Request $request)
    {

        $channels = Channel
        ::withCount('users')
        ->orderByDesc('users_count')
        ->get();

        return view('channels.index', compact('channels'));
    }

}
