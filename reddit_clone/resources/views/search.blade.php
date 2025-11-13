@extends('layouts.app')
@section('title', 'Risultati ricerca')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Risultati per "{{ request('q') }}"</h1>

    @if($posts->count() > 0)
        @foreach($posts as $post)
            <div class="bg-white shadow p-4 mb-4 rounded-lg">
                <a href="{{ route('posts.show', $post->id) }}" class="text-xl font-bold">{{ $post->title }}</a>
                <p class="text-gray-600">di <a href="/users/{{ $post->user->id }}" class="text-blue-600">{{ $post->user->name }}</a></p>
            </div>
        @endforeach
    @else
        <p>Nessun risultato trovato.</p>
    @endif
@endsection
