@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h1 class="text-3xl font-bold text-slate-800">
            Import Devices
        </h1>

        <p class="text-slate-500 mt-2">
            Upload Excel file containing Airport Information Display devices.
        </p>

        @if(session('success'))

            <div class="mt-6 rounded-xl bg-green-100 text-green-700 p-4">
                {{ session('success') }}
            </div>

        @endif

        @if($errors->any())

            <div class="mt-6 rounded-xl bg-red-100 text-red-700 p-4">

                <ul class="list-disc ml-5">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="{{ route('import.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="mt-8 space-y-6">

            @csrf

            <input
                type="file"
                name="file"
                accept=".xlsx,.xls"
                class="block w-full rounded-xl border border-slate-300 p-3">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition">

                Import Excel

            </button>

        </form>

    </div>

</div>

@endsection