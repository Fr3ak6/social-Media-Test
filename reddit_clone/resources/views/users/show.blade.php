@extends('layouts.app')
@section('title', $user->name)

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ $user->name }}</h1>
    <p class="mb-6 text-gray-600">Post totali: {{ $user->posts->count() }} |<!-- Karma: {{ /*$user->posts->flatMap->votes->sum('value')*/ }}--></p>

    <h2 class="text-xl font-semibold mb-3">Post recenti</h2>
    @foreach($user->posts as $post)
        <div class="bg-white shadow p-4 mb-3 rounded-lg">
            <a href="{{ route('posts.show', $post->id) }}" class="text-lg font-semibold">{{ $post->title }}</a>
            <p class="text-sm text-gray-500">Pubblicato {{ $post->created_at->diffForHumans() }}</p>
        </div>
    @endforeach
@endsection
 