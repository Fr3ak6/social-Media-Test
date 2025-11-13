<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Reddit Clone')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <a href="/" class="text-xl font-bold">🔺 Reddit Clone</a>
        <div>
            @auth
                <a href="/dashboard" class="mr-4">Dashboard</a>
                <a href="/logout">Logout</a>
            @else
                <a href="/login" class="mr-4">Login</a>
                <a href="/register">Register</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-5xl mx-auto mt-6 px-4">
        @yield('content')
    </main>
</body>
</html>
