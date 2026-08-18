<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-8">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}"
                   class="font-bold text-lg text-gray-800 dark:text-white">
                    FIDS Monitoring
                </a>

                <!-- Navigation -->
                <div class="hidden sm:flex items-center gap-6">

                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium
                       {{ request()->routeIs('dashboard')
                            ? 'text-blue-600'
                            : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('devices') }}"
                       class="text-sm font-medium
                       {{ request()->routeIs('devices*')
                            ? 'text-blue-600'
                            : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                        Devices
                    </a>

                    <a href="{{ route('monitoring') }}"
                       class="text-sm font-medium
                       {{ request()->routeIs('monitoring')
                            ? 'text-blue-600'
                            : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                        Monitoring
                    </a>

                    <a href="{{ route('history') }}"
                       class="text-sm font-medium
                       {{ request()->routeIs('history')
                            ? 'text-blue-600'
                            : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                        History
                    </a>

                    <a href="{{ route('notifications') }}"
                       class="text-sm font-medium
                       {{ request()->routeIs('notifications')
                            ? 'text-blue-600'
                            : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                        Notifications
                    </a>

                    @if(auth()->user()->role === 'admin')

                        <a href="{{ route('users.index') }}"
                           class="text-sm font-medium
                           {{ request()->routeIs('users*')
                                ? 'text-blue-600'
                                : 'text-gray-600 dark:text-gray-300 hover:text-blue-600' }}">
                            User Management
                        </a>

                    @endif

                </div>

            </div>


            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- User -->
                <div class="text-right hidden sm:block">

                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>

                </div>


                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg
                               bg-red-500 text-white
                               text-sm font-medium
                               hover:bg-red-600
                               transition">

                        Log Out

                    </button>

                </form>

            </div>

        </div>

    </div>
</nav>