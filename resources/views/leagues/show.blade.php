<x-layouts.app>
    <div class="max-w-5xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold text-orange-600 mb-6">
            {{ $league }}
        </h2>

        @if ($teams->isEmpty())
            <p class="text-gray-600">Šajā līgā nav reģistrētu komandu.</p>
        @else
            <table class="w-full border border-gray-300 rounded">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Nosaukums</th>
                        <th class="p-2 text-left">Valsts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr class="border-t">
                            <td class="p-2">{{ $team->name }}</td>
                            <td class="p-2">{{ $team->country }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="mt-6">
            <a href="{{ route('leagues.index') }}" class="text-orange-600 hover:underline">← Atpakaļ uz līgām</a>
        </div>
    </div>
</x-layouts.app>
