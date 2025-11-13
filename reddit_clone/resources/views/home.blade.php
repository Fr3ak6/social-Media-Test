@extends('layouts.app')
@section('title', 'Home')

@section('content')

<div x-data="{ openModal: false }">

{{-- Loader --}}
<div id="loader" class="hidden mb-6">
    <div class="flex justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-red-600"></div>
    </div>
</div>

<h1 class="text-2xl font-semibold mb-4 flex justify-between items-center">
    <span data-aos="fade-right">Ultimi Post</span>
    @auth
        <a href="{{ url('/posts/create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition-transform transform hover:scale-105">
            + Crea Post
        </a>
    @endauth
</h1>

<form method="GET" action="/" class="mb-6 flex gap-2" data-aos="fade-up">
    <input type="text" name="search" placeholder="Cerca post o tag..." class="border p-2 rounded w-full transition-shadow focus:shadow-md">

    <select name="sort" class="border p-2 rounded transition-colors focus:border-red-500">
        <option value="latest">Più recenti</option>
        <option value="top">Più votati</option>
    </select>

    <a href="{{ route('channels.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition-transform hover:scale-105">
        Canali
    </a>

    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors active:bg-red-700">
        Filtra
    </button>
</form>

{{-- Post da controller --}}
@foreach($posts as $post)
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4 transition-transform duration-300 hover:shadow-xl hover:scale-[1.02]" data-aos="fade-up">
        <a href="{{ route('posts.show', $post->id) }}" class="text-xl font-bold hover:underline transition-colors">{{ $post->title }}</a>
        <p class="text-gray-600">di 
            <a href="/users/{{ $post->user->id }}" class="text-blue-600 hover:underline" title="Vedi profilo">
                {{ $post->user->name }}
            </a> - {{ $post->created_at->diffForHumans() }}
        </p>
        <p class="mt-2 text-gray-700">{{ Str::limit($post->content, 150) }}</p>

        @if ($post->image_path)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Immagine del post" class="rounded-lg max-w-full h-auto">
            </div>
        @endif


        <div class="mt-2 text-sm text-gray-600">
            @foreach($post->tags as $tag)
                <a href="{{ route('tags.show', $tag->name) }}" 
                   class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded mr-1 text-xs hover:bg-gray-300 transition"
                   title="Tag: {{ $tag->name }}">
                   #{{ $tag->name }}
                </a>
            @endforeach
        </div>
        <p class="text-sm text-gray-500 mt-2">Voti: {{ $post->votes_total ?? $post->votes()->sum('value') }} | Commenti: {{ $post->comments->count() }}</p>
    </div>
@endforeach

{{-- Post da API (fetch) --}}
<div id="api-posts" class="mt-10">
    <h2 class="text-xl font-semibold mb-4">Post caricati via API REST</h2>
    <div id="api-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Errore nel caricamento API:</strong>
        <span id="error-message"></span>
    </div>
    <div id="api-loading" class="text-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-blue-600 mx-auto"></div>
        <p class="mt-2 text-gray-600">Caricamento post da API...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const apiContainer = document.getElementById('api-posts');
        const errorDiv = document.getElementById('api-error');
        const errorMessage = document.getElementById('error-message');
        const loadingDiv = document.getElementById('api-loading');
        
        // Debug: mostra URL che stai chiamando
        console.log('Chiamando API:', window.location.origin + '/api/posts');
        
        fetch('/api/posts', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            loadingDiv.style.display = 'none';
            
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(post => {
                    const el = document.createElement('div');
                    el.className = "bg-gray-100 p-4 mb-4 rounded shadow border-l-4 border-blue-500";
                    
                    // Gestisci i tag in modo sicuro
                    const tagsHtml = post.tags && post.tags.length > 0 
                        ? post.tags.map(tag => `#${tag.name}`).join(', ')
                        : 'Nessun tag';
                    
                    el.innerHTML = `
                        <h3 class="text-lg font-bold text-blue-800">${post.title}</h3>
                        <p class="text-sm text-gray-600">Autore: ${post.user ? post.user.name : 'Sconosciuto'}</p>
                        <p class="mt-1 text-gray-700">${post.content.substring(0, 200)}${post.content.length > 200 ? '...' : ''}</p>
                        <p class="text-sm text-gray-500 mt-2">Tag: ${tagsHtml}</p>
                        <p class="text-sm text-gray-500">Voti: ${post.votes_total || 0} | Commenti: ${post.comments_count || 0}</p>
                        <p class="text-xs text-gray-400 mt-1">Caricato da API REST • ID: ${post.id}</p>
                    `;
                    apiContainer.appendChild(el);
                });
            } else {
                const noDataEl = document.createElement('div');
                noDataEl.className = "bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded";
                noDataEl.innerHTML = `<p>Nessun post trovato tramite API.</p>`;
                apiContainer.appendChild(noDataEl);
            }
        })
        .catch(error => {
            console.error('API Error:', error);
            loadingDiv.style.display = 'none';
            errorDiv.classList.remove('hidden');
            errorMessage.textContent = error.message;
            
            // Mostra anche un messaggio di fallback
            const fallbackEl = document.createElement('div');
            fallbackEl.className = "bg-gray-100 p-4 rounded";
            fallbackEl.innerHTML = `
                <p class="text-gray-600">
                    Non è stato possibile caricare i post tramite API. 
                    Controlla la console per maggiori dettagli.
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Errore: ${error.message}
                </p>
            `;
            apiContainer.appendChild(fallbackEl);
        });
    });
</script>

@endsection