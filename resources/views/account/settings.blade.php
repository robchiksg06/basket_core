<x-layouts.app>
<div class="max-w-2xl mx-auto py-10 px-4">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Konta iestatījumi</h1>
        <p class="text-gray-400 text-sm mt-1">{{ Auth::user()->email }}</p>
    </div>

    {{-- Profila bilde --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
            <h2 class="text-lg font-bold text-white uppercase tracking-wide">Profila bilde</h2>
        </div>
        <div class="p-8">
            @if(session('success_avatar'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 font-medium">
                    ✓ {{ session('success_avatar') }}
                </div>
            @endif

            <div class="flex items-center gap-6 mb-6">
                @if(Auth::user()->avatarUrl())
                    <img src="{{ Auth::user()->avatarUrl() }}"
                         class="w-20 h-20 rounded-2xl object-cover border-4 border-orange-100 shadow"
                         alt="Profila bilde">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center text-2xl font-black shadow">
                        {{ strtoupper(mb_substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ Auth::user()->avatarUrl() ? 'Augšupielādēta bilde' : 'Vēl nav augšupielādēta bilde' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('account.avatar') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Augšupielādēt jaunu bildi</label>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-slate-600
                                  file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                                  file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700
                                  hover:file:bg-orange-100 cursor-pointer">
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG vai WebP · Maks. 2 MB</p>
                </div>
                <button type="submit"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition">
                    Saglabāt bildi
                </button>
            </form>
        </div>
    </div>

    {{-- Profila info --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-5">
            <h2 class="text-lg font-bold text-white uppercase tracking-wide">Profila informācija</h2>
        </div>
        <div class="p-8">
            @if(session('success_profile'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 font-medium">
                    ✓ {{ session('success_profile') }}
                </div>
            @endif

            <form method="POST" action="{{ route('account.profile') }}" class="space-y-5">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Vārds</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                           class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">E-pasts</label>
                    <input type="text" value="{{ Auth::user()->email }}" disabled
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">E-pastu mainīt nav iespējams</p>
                </div>
                <button type="submit"
                        class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition">
                    Saglabāt
                </button>
            </form>
        </div>
    </div>

    {{-- Paroles maiņa --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-5">
            <h2 class="text-lg font-bold text-white uppercase tracking-wide">Mainīt paroli</h2>
        </div>
        <div class="p-8">
            @if(session('success_password'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 font-medium">
                    ✓ {{ session('success_password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('account.password') }}" class="space-y-5">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pašreizējā parole</label>
                    <input type="password" name="current_password" required
                           class="w-full border {{ $errors->has('current_password') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jaunā parole</label>
                    <input type="password" name="password" required
                           class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Apstiprināt jauno paroli</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <p class="text-xs text-gray-400">Minimums 8 rakstzīmes</p>
                <button type="submit"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition">
                    Mainīt paroli
                </button>
            </form>
        </div>
    </div>

</div>
</x-layouts.app>
