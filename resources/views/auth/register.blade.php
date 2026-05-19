<x-layout>
    <div class="w-full max-w-md mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center text-orange-600 mb-6">Reģistrēties BasketCore</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-gray-700 font-medium">Vārds</label>
                <input type="text" name="name" id="name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Jānis Bērziņš" required>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium">E-pasts</label>
                <input type="email" name="email" id="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="example@basketcore.com" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium">Parole</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="••••••••" required>
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium">Apstiprināt paroli</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="••••••••" required>
            </div>

            <button type="submit"
                class="w-full bg-orange-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-700 transition duration-200">
                Reģistrēties
            </button>
        </form>

        <p class="mt-4 text-center text-gray-600 text-sm">
            Jau ir konts?
            <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Ieiet šeit</a>
        </p>
    </div>
</x-layout>
