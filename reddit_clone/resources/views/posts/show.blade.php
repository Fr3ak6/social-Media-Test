@extends('layouts.app')

@section('title', $post->title)


@section('content')
    <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
    @if(auth()->check() && auth()->id() === $post->user_id)
        <a href="{{ route('posts.edit', $post) }}" class="inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mb-4">
            ✏️ Modifica
        </a>
    @endif

    <p class="mb-4">{{ $post->content }}</p>

    @if ($post->image_path)
        <div class="my-4">
            <img src="{{ asset('storage/' . $post->image_path) }}" 
                alt="Immagine del post" 
                class="max-w-full h-auto rounded-lg shadow-md">
        </div>
    @endif

    {{-- Voti del post --}}
    <div class="mb-4 votable-box">
        <p class="font-semibold">Voti: <span class="vote-count">{{ $post->votes()->sum('value') }}</span></p>
    
        <form action="{{ route('vote') }}" method="POST" class="vote-form inline">
            @csrf
            <input type="hidden" name="type" value="post">
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="value" value="1">
            <button type="submit">👍</button>
        </form>
    
        <form action="{{ route('vote') }}" method="POST" class="vote-form inline">
            @csrf
            <input type="hidden" name="type" value="post">
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="value" value="-1">
            <button type="submit">👎</button>
        </form>
    </div>
    
    <hr class="my-4">

    <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Commenti</h3>
    
        @forelse ($post->comments as $comment)
            <div class="mb-4 p-4 bg-white border rounded shadow-sm votable-box">
                <p class="mb-2 text-gray-700">{{ $comment->content }}</p>
    
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>Voti: <span class="font-semibold vote-count">{{ $comment->votes()->sum('value') }}</span></span>
    
                    {{-- Pulsanti voto --}}
                    <form action="{{ route('vote') }}" method="POST" class="vote-form inline">
                        @csrf
                        <input type="hidden" name="type" value="comment">
                        <input type="hidden" name="id" value="{{ $comment->id }}">
                        <input type="hidden" name="value" value="1">
                        <button type="submit" class="hover:text-green-600">👍</button>
                    </form>
    
                    <form action="{{ route('vote') }}" method="POST" class="vote-form inline">
                        @csrf
                        <input type="hidden" name="type" value="comment">
                        <input type="hidden" name="id" value="{{ $comment->id }}">
                        <input type="hidden" name="value" value="-1">
                        <button type="submit" class="hover:text-red-600">👎</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Nessun commento ancora. Sii il primo!</p>
        @endforelse
    
        <hr class="my-6">
    
        <h3 class="text-lg font-semibold mb-2 text-gray-700">Aggiungi un commento</h3>
        <form method="POST" action="{{ route('posts.comment', $post->id) }}">
            @csrf
            <textarea name="content" rows="3" class="w-full border border-gray-300 rounded p-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Scrivi un commento..."></textarea>
            <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-black">Invia</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('form.vote-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            fetch("{{ route('vote') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const voteDisplay = form.closest('.votable-box').querySelector('.vote-count');
                voteDisplay.innerText = data.totalVotes;
            });
        });
    });
</script>
@endsection