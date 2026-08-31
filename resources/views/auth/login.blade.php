<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 sm:p-9 space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/angkasapura-logo.jpg') }}" alt="Angkasa Pura | Airports" class="h-10 mx-auto object-contain">
            <p class="text-xs font-bold text-[#0072bc] tracking-wider uppercase">
                FIDS Monitoring System
            </p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Alert -->
        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
            @csrf

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
                    autofocus
                    autocomplete="username"
                    placeholder="nama@gmail.com"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block font-semibold text-slate-700">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] text-[#0072bc] hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-3.5 pr-10 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0072bc] focus:border-[#0072bc] transition">
                    
                    <button
                        type="button"
                        onclick="togglePasswordVisibility()"
                        tabindex="-1"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="w-4 h-4 rounded text-[#0072bc] border-slate-300 focus:ring-[#0072bc] cursor-pointer">
                    <span class="text-xs text-slate-600">
                        Ingat saya
                    </span>
                </label>
            </div>

            <!-- Submit -->
            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-semibold shadow-md transition">
                    Login
                </button>
            </div>
        </form>

        @if (Route::has('register'))
        <div class="text-center pt-2 border-t border-slate-100">
            <p class="text-slate-500 text-xs">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#0072bc] font-bold hover:underline">
                    Daftar
                </a>
            </p>
        </div>
        @endif

    </div>

    <script>
        function togglePasswordVisibility() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                pwd.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>

</x-guest-layout>
