@extends('layouts.app')
@section('title', 'Crea nuovo post')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Crea un nuovo post</h1>

    <form method="POST" action="/posts" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Titolo" class="w-full p-2 mb-4 border rounded" required>

        <div class="mb-4">
            <label for="channel" class="block text-sm font-medium text-gray-700">Canale</label>
            <select name="channel_id" id="channel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Nessun canale --</option>
                @foreach($userChannels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </select>
        </div>

        <textarea name="content" placeholder="Scrivi qui il tuo contenuto..." rows="5" class="w-full p-2 mb-4 border rounded" required></textarea>

        <div class="mb-4 flex items-center relative">
            <label class="block mb-1 text-sm mr-2">Tag (separati da virgola)</label>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" class="size-6 cursor-pointer" onclick="toggleTooltip(event)">
                <path strokeLinecap="round" strokeLinejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
            </svg>
            <div class="tooltip hidden absolute bg-gray-700 text-white text-xs rounded p-2 w-48 -mt-8" style="left: 50%; transform: translateX(-50%);">
                Questi tag ti aiutano a classificare e trovare facilmente i contenuti correlati. Clicca su un tag per visualizzare tutti i post associati. Puoi anche filtrare i contenuti in base ai tuoi interessi!
            </div>
        </div>

        <input type="text" name="tags" class="w-full border p-2 rounded" placeholder="gaming, coding, ecc..."><br>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Immagine (opzionale)</label>
            <input type="file" name="image" class="block w-full text-sm text-gray-900 border rounded cursor-pointer bg-gray-50 focus:outline-none">
        </div>
        
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Pubblica</button>
    </form>

    <script>
        function toggleTooltip(event) {
            const tooltip = event.target.nextElementSibling;
            tooltip.classList.toggle('hidden');
            setTimeout(() => tooltip.classList.add('hidden'), 8000); // Nasconde dopo 2 secondi
        }
    </script>
@endsection