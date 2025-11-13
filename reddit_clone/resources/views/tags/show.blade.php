@extends('layouts.app')
@section('title', "#$tag->name")

@section('content')
<h1 class="text-2xl font-bold mb-4">Post con tag #{{ $tag->name }}</h1>

@foreach($posts as $post)
    <div class="bg-white p-4 rounded shadow mb-4">
        <a href="{{ route('posts.show', $post->id) }}" class="text-xl font-semibold">{{ $post->title }}</a>
        <p class="text-gray-500 text-sm">di {{ $post->user->name }} • Voti: {{ $post->votes->sum('value') }}</p>
    </div>
@endforeach

{{ $posts->links() }}
@endsection
