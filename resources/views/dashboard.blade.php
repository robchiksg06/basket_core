<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

</body>
<x-layouts.app>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-orange-600 mb-6">Sveiks, {{ Auth::user()->name }} 👋</h1>

        <p class="text-gray-700 dark:text-gray-300 mb-8">
            Laipni lūgts BasketCore pārvaldības panelī. Izvēlies sadaļu, kuru vēlies apskatīt vai rediģēt:
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('players.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-md transition transform hover:-translate-y-1 hover:scale-[1.01] border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🏀</div>
                <h2 class="text-lg font-bold text-orange-600">Spēlētāji</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Apskati un pārvaldi spēlētāju sarakstu.</p>
            </a>

            <a href="{{ route('teams.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-md transition transform hover:-translate-y-1 hover:scale-[1.01] border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">👕</div>
                <h2 class="text-lg font-bold text-orange-600">Komandas</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pievieno vai rediģē komandas.</p>
            </a>

            <a href="{{ route('leagues.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-md transition transform hover:-translate-y-1 hover:scale-[1.01] border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🏆</div>
                <h2 class="text-lg font-bold text-orange-600">Līgas</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Skaties vai pārvaldi dažādas līgas.</p>
            </a>

            <a href="{{ route('coaches.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-md transition transform hover:-translate-y-1 hover:scale-[1.01] border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🧠</div>
                <h2 class="text-lg font-bold text-orange-600">Treneri</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Treneru profilu saraksts un pārvaldība.</p>
            </a>
        </div>
    </div>
</x-layouts.app>
