<!DOCTYPE html>
<html lang="lv" class="light">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BasketCore' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col bg-white dark:bg-slate-950 transition-colors duration-200">

<header class="w-full bg-gray-900 text-white shadow-md relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-500 flex-shrink-0">🏀 BasketCore</a>

        {{-- Desktop nav --}}
        <nav class="hidden md:flex items-center gap-5 text-sm">
            <a href="{{ route('home') }}" class="text-white hover:text-orange-400 transition">Sākums</a>
            <a href="{{ route('forum.index') }}" class="text-white hover:text-orange-400 transition">Forums</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-white hover:text-orange-400 transition">Panelis</a>

                {{-- Profesionālais dropdown --}}
                <div class="relative" id="dd-pro">
                    <button onclick="toggleDropdown('dd-pro')"
                            class="flex items-center gap-1 text-white hover:text-orange-400 transition">
                        Profesionālais
                        <svg class="w-3.5 h-3.5 mt-0.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dd-menu absolute left-0 top-full mt-2 hidden z-50">
                        <div class="bg-gray-800 border border-white/10 rounded-xl shadow-xl overflow-hidden min-w-[180px]">
                            <a href="{{ route('players.public') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">👤 Spēlētāji</a>
                            <a href="{{ route('teams.index') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">👕 Komandas</a>
                            <a href="{{ route('leagues.index') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">🏅 Līgas</a>
                            <div class="border-t border-white/10 my-1"></div>
                            <a href="{{ route('players.compare') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">⚖️ Salīdzināt</a>
                        </div>
                    </div>
                </div>

                {{-- Manas spēles dropdown --}}
                <div class="relative" id="dd-play">
                    <button onclick="toggleDropdown('dd-play')"
                            class="flex items-center gap-1 text-white hover:text-orange-400 transition">
                        Manas spēles
                        <svg class="w-3.5 h-3.5 mt-0.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dd-menu absolute left-0 top-full mt-2 hidden z-50">
                        <div class="bg-gray-800 border border-white/10 rounded-xl shadow-xl overflow-hidden min-w-[180px]">
                            <a href="{{ route('games.index') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">📋 Spēles</a>
                            <a href="{{ route('games.create') }}" class="block px-4 py-2.5 text-orange-400 hover:bg-white/10 transition font-semibold">+ Jauna spēle</a>
                            <div class="border-t border-white/10 my-1"></div>
                            <a href="{{ route('tournaments.index') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">🏆 Turnīri</a>
                            <a href="{{ route('tournaments.create') }}" class="block px-4 py-2.5 text-orange-400 hover:bg-white/10 transition font-semibold">+ Jauns turnīrs</a>
                            <div class="border-t border-white/10 my-1"></div>
                            <a href="{{ route('leaderboard') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition">📊 TOP saraksts</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('account.settings') }}" title="Konta iestatījumi" class="flex-shrink-0">
                    @if(Auth::user()->avatarUrl())
                        <img src="{{ Auth::user()->avatarUrl() }}"
                             class="w-8 h-8 rounded-lg object-cover border-2 border-white/20 hover:border-orange-400 transition"
                             alt="Profils">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs font-bold transition border-2 border-transparent hover:border-orange-400">
                            {{ strtoupper(mb_substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
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

            {{-- Dark mode toggle --}}
            <button onclick="toggleDarkMode()" id="theme-toggle"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white transition"
                    title="Mainīt tēmu">
                <svg id="icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg id="icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
        </nav>

        {{-- Hamburger + theme toggle --}}
        <div class="md:hidden flex items-center gap-2">
            <button onclick="toggleDarkMode()"
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white transition">
                <svg id="icon-moon-mob" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg id="icon-sun-mob" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
            <button id="nav-toggle"
                    class="flex flex-col justify-center items-center w-10 h-10 gap-1.5 rounded-lg hover:bg-white/10 transition"
                    aria-label="Navigācija">
                <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all origin-center"></span>
                <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all"></span>
                <span class="hamburger-bar block w-6 h-0.5 bg-white transition-all origin-center"></span>
            </button>
        </div>
    </div>

    {{-- Mobile nav --}}
    <nav id="mobile-nav"
         class="md:hidden hidden flex-col bg-gray-800 border-t border-white/10 px-4 py-3 space-y-1 text-sm">
        <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Sākums</a>
        <a href="{{ route('forum.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Forums</a>
        @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">Panelis</a>
            <div class="px-4 py-2 text-xs font-bold text-white/30 uppercase tracking-widest">Profesionālais</div>
            <a href="{{ route('players.public') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">👤 Spēlētāji</a>
            <a href="{{ route('teams.index') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">👕 Komandas</a>
            <a href="{{ route('leagues.index') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">🏅 Līgas</a>
            <a href="{{ route('players.compare') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">⚖️ Salīdzināt</a>
            <div class="px-4 py-2 text-xs font-bold text-white/30 uppercase tracking-widest mt-1">Manas spēles</div>
            <a href="{{ route('games.index') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">📋 Spēles</a>
            <a href="{{ route('games.create') }}" class="block px-4 py-2 ml-4 rounded-xl hover:bg-white/10 transition text-orange-400 font-semibold">+ Jauna spēle</a>
            <a href="{{ route('tournaments.index') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">🏆 Turnīri</a>
            <a href="{{ route('tournaments.create') }}" class="block px-4 py-2 ml-4 rounded-xl hover:bg-white/10 transition text-orange-400 font-semibold">+ Jauns turnīrs</a>
            <a href="{{ route('leaderboard') }}" class="block px-4 py-2.5 ml-2 rounded-xl hover:bg-white/10 transition">📊 TOP saraksts</a>
            <a href="{{ route('account.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition text-white/70">
                @if(Auth::user()->avatarUrl())
                    <img src="{{ Auth::user()->avatarUrl() }}" class="w-7 h-7 rounded-lg object-cover flex-shrink-0" alt="">
                @else
                    <div class="w-7 h-7 rounded-lg bg-white/10 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(mb_substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                Konta iestatījumi
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

<main class="flex-grow text-gray-900 dark:text-slate-100">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 pt-4">
            <div class="rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 text-sm">
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
    // Dark mode
    function toggleDarkMode() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateIcons(isDark);
    }

    function updateIcons(isDark) {
        ['', '-mob'].forEach(suffix => {
            const moon = document.getElementById('icon-moon' + suffix);
            const sun  = document.getElementById('icon-sun'  + suffix);
            if (moon) moon.classList.toggle('hidden',  isDark);
            if (sun)  sun.classList.toggle('hidden',  !isDark);
        });
    }

    updateIcons(document.documentElement.classList.contains('dark'));

    // Mobile nav
    const toggle = document.getElementById('nav-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const bars = document.querySelectorAll('.hamburger-bar');

    toggle?.addEventListener('click', function () {
        const isOpen = mobileNav.style.display === 'flex';
        mobileNav.style.display = isOpen ? 'none' : 'flex';
        mobileNav.classList.toggle('hidden', isOpen);
        bars[0].style.transform = isOpen ? '' : 'translateY(8px) rotate(45deg)';
        bars[1].style.opacity   = isOpen ? '' : '0';
        bars[2].style.transform = isOpen ? '' : 'translateY(-8px) rotate(-45deg)';
    });

    function toggleDropdown(id) {
        const wrapper = document.getElementById(id);
        const menu = wrapper.querySelector('.dd-menu');
        const isOpen = !menu.classList.contains('hidden');
        document.querySelectorAll('.dd-menu').forEach(m => m.classList.add('hidden'));
        if (!isOpen) menu.classList.remove('hidden');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[id^="dd-"]')) {
            document.querySelectorAll('.dd-menu').forEach(m => m.classList.add('hidden'));
        }
    });
</script>

</body>
</html>
