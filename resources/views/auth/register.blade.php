<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 sm:p-9 space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/injourney-logo.png') }}" alt="InJourney Airports" class="h-12 mx-auto object-contain">
            <p class="text-xs font-bold text-[#0072bc] tracking-wider uppercase">
                FIDS Monitoring System
            </p>
        </div>

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-semibold text-slate-700 mb-1.5">
                    Nama
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nama Lengkap"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-semibold text-slate-700 mb-1.5">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="nama@gmail.com"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-semibold text-slate-700 mb-1.5">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block font-semibold text-slate-700 mb-1.5">
                    Konfirmasi Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-semibold shadow-md transition">
                    Daftar
                </button>
            </div>
        </form>

        <div class="text-center pt-2 border-t border-slate-100">
            <p class="text-slate-500 text-xs">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#0072bc] font-bold hover:underline">
                    Login
                </a>
            </p>
        </div>

    </div>

</x-guest-layout>
