<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Laravel')</title>
    <meta name="description" content="@yield('description', 'Laravel')" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @vite(['resources/css/app.css'])
    @stack('css')

    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;800&display=swap" />
    <link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;800&display=swap" />
</head>
<body class="antialiased">
    <header class="border-b shadow">
        <div class="xl:container px-1 flex justify-between">
            <a href="/" class="py-3 px-3 inline-flex items-center hover:bg-gray-100">Home</a>

            @if (Route::has('login'))
                <div class="flex justify-between">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-3 py-3 hover:bg-gray-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-3 hover:bg-gray-100">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-3 px-3 hover:bg-gray-100">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

        </div>
    </header>
    <main>@yield('content')</main>
    <footer>
        <a href="{{ route('projects.index') }}">Projects</a>
    </footer>
    @stack('js')
</body>
</html>