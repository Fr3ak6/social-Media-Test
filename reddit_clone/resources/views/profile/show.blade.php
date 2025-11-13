<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Profilo di {{ $profile->name }}</h2>
    </x-slot>

    <x-slot name="div">
        <div class="max-w-4xl mx-auto bg-white shadow p-6 rounded">
            <p><strong>Nome:</strong> {{ $profile->name }}</p>
            <p><strong>Email:</strong> {{ $profile->email }}</p>
            <p><strong>Registrato il:</strong> {{ $profile->created_at->format('d/m/Y') }}</p>
        </div> 
    </x-slot>
</x-app-layout>
