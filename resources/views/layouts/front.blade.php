<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Laravel') | RexWebMedia</title>
    <meta name="description" content="@yield('description', 'Laravel')" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @stack('css')
</head>
<body class="antialiased">
    <header>

    </header>
    <main>@yield('content')</main>
    <footer>

    </footer>
    @stack('js')
</body>
</html>