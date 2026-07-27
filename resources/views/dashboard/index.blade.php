@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-8">
    Dashboard
</h1>

<div class="grid grid-cols-4 gap-6">

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Total Device</p>
        <h2 class="text-4xl font-bold mt-2">
            {{ $total }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Online</p>
        <h2 class="text-4xl font-bold text-green-600 mt-2">
            {{ $online }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Offline</p>
        <h2 class="text-4xl font-bold text-red-600 mt-2">
            {{ $offline }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500">Warning</p>
        <h2 class="text-4xl font-bold text-yellow-500 mt-2">
            {{ $warning }}
        </h2>
    </div>

</div>

@endsection