<!DOCTYPE html>
<html lang="lv" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BasketCore' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col">

<header class="w-full bg-gray-900 text-white shadow-md relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-500 flex-shrink-0">🏀 BasketCore</a>

        {{-- Desktop nav --}}
        <nav class="hidden md:flex items-center gap-5 text-sm">
            <a href="{{ route('home') }}" class="text-white hover:text-orange-400 transition">Sākums</a>
            <a href="{{ route('forum.index') }}" class="text-white hover:text-orange-400 transition">Forums</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-white hover:text-orange-400 transition">Vadības panelis</a>
                <a href="{{ route('games.index') }}" class="text-white hover:text-orange-400 transition">Spēles</a>
                <a href="{{ route('games.create') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    + Jauna spēle
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white hover:text-red-400 transition">Iziet</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-white hover:text-orange-400 transition">Ieiet</a>
                <a href="{{ route('register') }}"
                   class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Reģistrēties
                </a>
            @endguest
        </nav>

        {{-- Hamburger --}}
        <button id="nav-toggle"
                class="md:hidden flex flex-col justify-center items-center w-10 h-10 gap-1.5 rounded-lg hover:bg-white/10 transition"
                aria-label="Navigācija">
            <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all origin-center"></span>
            <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all"></span>
            <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all origin-center"></span>
        </button>
    </div>

    {{-- Mobile nav --}}
    <nav id="mobile-nav"
         class="md:hidden hidden flex-col bg-gray-800 border-t border-white/10 px-4 py-3 space-y-1 text-sm">
        <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Sākums</a>
        <a href="{{ route('forum.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Forums</a>
        @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Vadības panelis</a>
            <a href="{{ route('games.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Spēles</a>
            <a href="{{ route('games.create') }}"
               class="block px-4 py-3 rounded-xl bg-orange-600 hover:bg-orange-700 font-bold text-center transition mt-2">
                + Jauna spēle
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 rounded-xl hover:bg-red-500/20 text-red-400 transition">
                    Iziet
                </button>
            </form>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Ieiet</a>
            <a href="{{ route('register') }}"
               class="block px-4 py-3 rounded-xl bg-orange-600 hover:bg-orange-700 font-bold text-center transition mt-2">
                Reģistrēties
            </a>
        @endguest
    </nav>
</header>

<main class="flex-grow bg-white text-gray-900">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif
    {{ $slot }}
</main>

<footer class="w-full bg-gray-900 text-white py-4 text-center text-sm">
    &copy; 2026, Roberts Mačs
</footer>

<script>
    const toggle = document.getElementById('nav-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const bars = document.querySelectorAll('.hamburger-bar');

    toggle?.addEventListener('click', function () {
        const isOpen = mobileNav.style.display === 'flex';
        mobileNav.style.display = isOpen ? 'none' : 'flex';
        mobileNav.classList.toggle('hidden', isOpen);
        bars[0].style.transform = isOpen ? '' : 'translateY(8px) rotate(45deg)';
        bars[1].style.opacity  = isOpen ? '' : '0';
        bars[2].style.transform = isOpen ? '' : 'translateY(-8px) rotate(-45deg)';
    });
</script>

</body>
</html>
