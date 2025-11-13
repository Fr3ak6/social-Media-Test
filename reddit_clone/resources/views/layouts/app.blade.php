<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-all duration-300">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RedditClone') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #494949;
            color: #1f2937;
        }

        .notification {
            animation: fadeInOut 4s ease-in-out forwards;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body class="antialiased leading-relaxed">
    <div class="min-h-screen bg-gray-50">
        @include('layouts.navigation') <!-- Assicurati che sia presente solo qui -->

        @if(session('success'))
            <div class="notification bg-green-100 border border-green-400 text-green-800 px-4 py-2 rounded mx-auto mt-4 max-w-4xl shadow-md">
                {{ session('success') }}
            </div>
        @endif

        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        
        <div class="flex">
            <aside class="w-64 bg-[#1c1c1c] border-r h-screen overflow-y-auto p-4 sticky top-0">
                <h2 class="text-lg font-semibold mb-4 text-white">I miei Canali</h2>
                <ul class="space-y-2">
                    @forelse(auth()->user()->channels as $channel)
                        <li>
                            <a href="{{ route('channels.show', $channel->id) }}" class="block p-2 rounded hover:bg-gray-100 text-white strong">
                                #{{ $channel->name }}
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-500 text-sm">Non sei iscritto a nessun canale</li>
                    @endforelse
                </ul>
            </aside>
        
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

