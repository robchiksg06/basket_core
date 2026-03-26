<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BasketCore' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('darkToggle');
            const html = document.documentElement;
            toggle?.addEventListener('change', function () {
                html.dataset.theme = this.checked ? 'dark' : 'light';
            });
        });
    </script>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <!-- Header -->
<header class="w-full bg-gray-900 text-white px-6 py-4 shadow-md">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-orange-500">🏀 BasketCore</h1>

            <label class="flex items-center gap-2 text-sm md:hidden">
                <span>Dark Mode</span>
                <input type="checkbox" id="darkToggle" class="form-checkbox">
            </label>
        </div>

        <div class="flex flex-wrap justify-end items-center gap-4">
            <a href="{{ route('home') }}" class="text-white hover:text-orange-400">Home</a>
            <a href="{{ route('dashboard') }}" class="text-white hover:text-orange-400">Dashboard</a>
            <a href="{{ route('forum.index') }}" class="text-white hover:text-orange-400">Forums</a>

            @auth
    
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white hover:text-red-400">Logout</button>
                </form>
                <a href="{{ route('games.index') }}" class="text-white hover:text-orange-400">Spēles</a>

<a href="{{ route('games.create') }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
    + Jauna spēle
</a>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="text-white hover:text-orange-400">Login</a>
                <a href="{{ route('register') }}" class="text-white hover:text-orange-400">Register</a>
            @endguest

            <label class="hidden md:flex items-center gap-2 text-sm">
                <span>Dark Mode</span>
                <input type="checkbox" id="darkToggle" class="form-checkbox">
            </label>
        </div>
    </div>
</header>

    

    <!-- Main content -->
    <main class="flex-grow p-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="w-full bg-gray-900 text-white p-4 text-center text-sm">
        &copy; 2024, Roberts Mačs
    </footer>
    

</body>
</html>

<style>
[data-theme='dark'] {
    background-color: #121212;
}
</style>


<style>
    [data-theme='dark'] {
        background-color: #121212;
        color: #e0e0e0;
    }
    
    [data-theme='dark'] header,
    [data-theme='dark'] footer {
        background-color: #1a1a1a;
        color: #e0e0e0;
    }
    
    [data-theme='dark'] a {
        color: #ff9800;
    }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('darkToggle');
            const html = document.documentElement;
    
            if (toggle) {
                toggle.checked = html.dataset.theme === 'dark'; // sinhronizācija
    
                toggle.addEventListener('change', function () {
                    html.setAttribute('data-theme', this.checked ? 'dark' : 'light');
                });
            }
        });
    </script>
    