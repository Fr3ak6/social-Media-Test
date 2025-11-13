<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Mostra la homepage con l'elenco dei post filtrati e ordinati.
     */
    public function index(Request $request)
    {
        // Carica relazioni per ottimizzare le query successive
        $query = Post::with(['user', 'tags', 'comments', 'votes']);

        // Filtro per parola chiave (titolo o contenuto)
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro per canale
        if ($request->filled('channel')) {
            $query->where('channel_id', $request->channel);
        }

        // Ordinamento: per voti se 'top', altrimenti per data di creazione
        if ($request->sort === 'top') {
            $query->withSum('votes', 'value')->orderByDesc('votes_sum_value');
        } else {
            $query->latest();
        }

        // Recupera i post con i filtri applicati
        $posts = $query->get();

        // Recupera i canali associati all'utente o tutti i canali se guest
        $channels = auth()->check() 
            ? auth()->user()->channels 
            : \App\Models\Channel::all();

        // Mostra la vista della homepage
        return view('home', compact('posts', 'channels'));
    }

    /**
     * Mostra un singolo post con tutti i dettagli e relazioni.
     */
    public function show($id)
    {
        $post = Post::with([
            'user',              // Autore del post
            'votes',             // Voti ricevuti
            'comments.user',     // Autori dei commenti
            'comments.votes'     // Voti sui commenti
        ])->findOrFail($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Cerca post per titolo o tag.
     */
    public function search(Request $request)
    {
        $q = $request->input('q');

        $posts = Post::with('user')
            ->where('title', 'like', "%$q%")
            ->orWhereHas('tags', fn($query) =>
                $query->where('name', 'like', "%$q%"))
            ->get();

        return view('search', compact('posts'));
    }

    /**
     * Mostra il form per creare un nuovo post.
     */
    public function create()
    {
        $userChannels = auth()->user()->channels()->get();
        return view('posts.create', compact('userChannels'));
    }

    /**
     * Salva un nuovo post nel database.
     */
    public function store(Request $request)
    {
        // Validazione dei dati in ingresso
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'channel_id' => 'nullable|exists:channels,id',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Gestione upload immagine, se presente
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // Creazione del post
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'channel_id' => $request->channel_id,
            'image_path' => $imagePath,
        ]);

        // Gestione dei tag
        if ($request->tags) {
            $tags = collect(explode(',', $request->tags))
                ->map(fn($t) => trim(Str::lower($t)))
                ->filter()
                ->unique();

            $tagIds = [];

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            // Associa i tag al post
            $post->tags()->sync($tagIds);
        }

        return redirect('/')->with('success', 'Post creato!');
    }

    /**
     * Aggiunge un commento a un post.
     */
    public function addComment(Request $request, $postId)
    {
        // Validazione contenuto commento
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Trova il post
        $post = Post::findOrFail($postId);

        // Crea il commento associato al post
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Commento aggiunto!');
    }

    public function edit(Post $post)
    {
        // Verifica che l'utente sia l'autore del post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Non sei autorizzato a modificare questo post.');
        }

        $userChannels = auth()->user()->channels()->get();
        $tagList = $post->tags->pluck('name')->implode(', ');

        return view('posts.edit', compact('post', 'userChannels', 'tagList'));
    }

        public function update(Request $request, Post $post)
        {
            // Verifica che l'utente sia l'autore del post
            if ($post->user_id !== auth()->id()) {
                abort(403, 'Non sei autorizzato a modificare questo post.');
            }

            // Validazione
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tags' => 'nullable|string',
                'channel_id' => 'nullable|exists:channels,id',
                'image' => 'nullable|image|max:2048',
            ]);

            // Aggiorna immagine se presente
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
                $post->image_path = $imagePath;
            }

            // Aggiorna post
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'channel_id' => $request->channel_id,
            ]);

            // Gestione tag
            if ($request->tags) {
                $tags = collect(explode(',', $request->tags))
                    ->map(fn($t) => trim(Str::lower($t)))
                    ->filter()
                    ->unique();

                $tagIds = [];

                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }

                $post->tags()->sync($tagIds);
            }

            return redirect()->route('posts.show', $post)->with('success', 'Post aggiornato con successo!');
        }

}
