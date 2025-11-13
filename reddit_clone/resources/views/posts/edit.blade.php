@extends('layouts.app')

@section('title', 'Modifica Post')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Modifica Post</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-semibold">Titolo</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="content" class="block font-semibold">Contenuto</label>
            <textarea name="content" rows="5" class="w-full border rounded p-2">{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="tags" class="block font-semibold">Tag (separati da virgola)</label>
            <input type="text" name="tags" value="{{ old('tags', $tagList) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="channel_id" class="block font-semibold">Canale</label>
            <select name="channel_id" class="w-full border rounded p-2">
                <option value="">Nessuno</option>
                @foreach($userChannels as $channel)
                    <option value="{{ $channel->id }}" {{ $post->channel_id == $channel->id ? 'selected' : '' }}>
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block font-semibold">Immagine (opzionale)</label>
            <input type="file" name="image" class="w-full border rounded p-2">
            @if ($post->image_path)
                <p class="mt-2"><img src="{{ asset('storage/' . $post->image_path) }}" class="max-w-sm"></p>
            @endif
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Aggiorna Post</button>
    </form>
@endsection
