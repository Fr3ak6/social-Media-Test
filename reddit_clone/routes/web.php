<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChannelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostApiController;

// Autenticazione Breeze
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Dashboard proteggo tutto da utenti non registrati
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');


// Profilo
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Post
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/create', [PostController::class, 'create'])->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->middleware('auth');
Route::post('/posts/{post}/comment', [PostController::class, 'addComment'])->name('posts.comment');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
// Modifica post
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');


// Commenti
Route::post('/comments', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');

// Voti
Route::post('/vote', [VoteController::class, 'vote'])->middleware('auth')->name('vote');

//Tags 
Route::get('/tags/{name}', [TagController::class, 'show'])->name('tags.show');

//Canali
Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
Route::get('/channels/{id}', [ChannelController::class, 'show'])->name('channels.show');
Route::post('/channels/{id}/join', [ChannelController::class, 'join'])->name('channels.join');
Route::post('/channels/{id}/leave', [ChannelController::class, 'leave'])->name('channels.leave');



// Profilo utente
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Ricerca
Route::get('/search', [PostController::class, 'search'])->name('posts.search');


Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');

// Rotte API temporanee (da rimuovere quando api.php funziona)


    
});

Route::prefix('api')->group(function () {
    Route::get('/test', function () {
        return response()->json([
            'message' => 'API temporanea funziona!',
            'timestamp' => now()
        ]);
    });
    
    Route::get('/posts', [PostApiController::class, 'index']);
    Route::post('/posts', [PostApiController::class, 'store']);
    Route::get('/posts/{post}', [PostApiController::class, 'show']);
    Route::put('/posts/{post}', [PostApiController::class, 'update']);
    Route::delete('/posts/{post}', [PostApiController::class, 'destroy']);

    
});





// Carica le route di Breeze
require __DIR__.'/auth.php';
