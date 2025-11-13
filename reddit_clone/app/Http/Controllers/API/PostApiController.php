<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with(['user', 'tags', 'votes', 'comments'])
                         ->orderBy('created_at', 'desc')
                         ->get();
                         
            // Aggiungi il conteggio voti calcolato
            $posts->each(function ($post) {
                $post->votes_total = $post->votes()->sum('value');
                $post->comments_count = $post->comments->count();
            });
            
            return response()->json($posts);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nel caricamento dei post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'user_id' => 'required|exists:users,id',
            ]);

            $post = Post::create($request->only(['title', 'content', 'user_id']));
            $post->load(['user', 'tags', 'votes']);

            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nella creazione del post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            $post->load(['user', 'tags', 'votes', 'comments']);
            $post->votes_total = $post->votes()->sum('value');
            $post->comments_count = $post->comments->count();
            
            return response()->json($post);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Post non trovato',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]);
            
            $post->update($request->only(['title', 'content']));
            $post->load(['user', 'tags', 'votes']);
            
            return response()->json($post);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nell\'aggiornamento del post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response()->json(['message' => 'Post eliminato con successo'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nell\'eliminazione del post',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}