<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<header class="bg-white shadow-md">
    <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-gray-800">
            {{ config('app.name', 'Laravel App') }}
        </div>
        <div class="space-x-4">
            @guest
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a>
                <a href="{{ route('register') }}" class="text-green-600 hover:text-green-800">Register</a>
            @else
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                   class="text-red-600 hover:text-red-800">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endguest
        </div>
    </nav>
</header>

<main class="container mx-auto px-4 py-8 flex-grow">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Welcome to {{ config('app.name', 'Laravel App') }}</h1>
        <p class="text-gray-600">
            @guest
                Please login or register to access the full features of our application.
            @else
                Welcome, {{ Auth::user()->name }}! You are now logged in.
            @endguest
        </p>
    </div>
</main>

<footer class="bg-gray-200 py-4 mt-8">
    <div class="container mx-auto px-4 text-center text-gray-600">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel App') }}. All rights reserved.
    </div>
</footer>
</body>
</html>
