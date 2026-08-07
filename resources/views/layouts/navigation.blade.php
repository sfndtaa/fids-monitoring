<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">

            <a href="{{ route('dashboard') }}" class="font-bold">
                FIDS Monitoring
            </a>

            <div class="flex items-center gap-4">

                <span>
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="text-red-600 hover:text-red-800">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>