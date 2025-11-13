@extends('layouts.app')

@section('title', 'Canali')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Canali Popolari</h1>

    @foreach($channels as $channel)
        <div class="bg-white shadow p-4 mb-4 rounded-lg">
            <h2 class="text-xl font-bold">#{{ $channel->name }}</h2>
            <p class="text-gray-600">Iscritti: {{ $channel->users_count }}</p>
            <a href="{{ route('channels.show', $channel->id) }}" class="text-blue-600 hover:underline">Visualizza Canale</a>
        </div>
    @endforeach
@endsection
