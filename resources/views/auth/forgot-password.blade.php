<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 sm:p-9 space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="InJourney Airports" class="h-12 mx-auto object-contain">
            <p class="text-xs font-semibold text-slate-500 tracking-wide uppercase">
                Lupa Password
            </p>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed text-center">
            Masukkan email Anda untuk menerima link reset password.
        </p>

        @if(session('status'))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-xs">
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
                    placeholder="nama@gmail.com"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition">
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-md transition">
                    Kirim Link Reset
                </button>
            </div>
        </form>

        <div class="text-center pt-2 border-t border-slate-100">
            <a href="{{ route('login') }}" class="text-slate-900 font-bold text-xs hover:underline">
                &larr; Kembali ke Login
            </a>
        </div>

    </div>

</x-guest-layout>
