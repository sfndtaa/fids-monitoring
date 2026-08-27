<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-9 space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="InJourney Airports" class="h-12 mx-auto object-contain">
            <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase">
                Reset Password
            </p>
        </div>

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-semibold text-slate-700 mb-1.5">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-semibold text-slate-700 mb-1.5">
                    Password Baru
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-semibold text-slate-700 mb-1.5">
                    Konfirmasi Password Baru
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-md transition">
                    Simpan Password Baru
                </button>
            </div>
        </form>

    </div>

</x-guest-layout>
