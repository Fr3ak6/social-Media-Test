@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">#{{ $channel->name }}</h1>

    @auth
        @if(Auth::user()->channels->contains($channel->id))
            <form method="POST" action="{{ route('channels.leave', $channel->id) }}">
                @csrf
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Abbandona</button>
            </form>
        @else
            <form method="POST" action="{{ route('channels.join', $channel->id) }}">
                @csrf
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Unisciti</button>
            </form>
        @endif
    @endauth

    <h2 class="text-xl font-semibold mt-6 mb-4">Post</h2>
    @forelse($channel->posts as $post)
        <div class="bg-white p-4 rounded shadow mb-3">
            <a href="{{ route('posts.show', $post->id) }}" class="text-lg font-semibold">{{ $post->title }}</a>
            <p class="text-gray-600">di <a href="/users/{{ $post->user->id }}" class="text-blue-600">{{ $post->user->name }}</a> - {{ $post->created_at->diffForHumans() }}</p>
            <p class="mt-2">{{ Str::limit($post->content, 150) }}</p>
            <div class="mt-2 text-sm text-gray-600">
                @foreach($post->tags as $tag)
                    <a href="{{ route('tags.show', $tag->name) }}" class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded mr-1 text-xs">#{{ $tag->name }}</a>
                @endforeach
            </div>            
                <p class="text-sm text-gray-500 mt-2">Voti: {{ $post->vote_sum }} | Commenti: {{ $post->comments->count() }}</p>
            </div>
        </div>
    @empty
        <p class="text-gray-500">Nessun post in questo canale.</p>
    @endforelse
@endsection
