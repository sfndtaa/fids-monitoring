@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto text-xs">

    <div class="bg-white rounded-lg shadow-xs border border-slate-300 p-6">

        <h1 class="text-lg font-bold text-slate-800">
            Import Devices
        </h1>

        <p class="text-slate-500 text-[11px] mt-0.5">
            Upload Excel file containing Airport Flight Information Display devices.
        </p>

        @if(session('success'))
            <div class="mt-4 rounded-md bg-emerald-100 border border-emerald-300 text-emerald-800 p-3 text-xs flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 rounded-md bg-rose-100 border border-rose-300 text-rose-800 p-3 text-xs">
                <ul class="list-disc ml-4 space-y-0.5">
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
            class="mt-6 space-y-4">

            @csrf

            <div>
                <label class="block mb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                    Select Excel File (.xlsx / .xls)
                </label>
                <input
                    type="file"
                    name="file"
                    accept=".xlsx,.xls"
                    required
                    class="block w-full rounded-md border border-slate-300 p-2.5 text-xs focus:ring-1 focus:ring-emerald-500 outline-none">
            </div>

            <button
                type="submit"
                class="bg-[#15803d] hover:bg-[#16a34a] text-white px-5 py-2 rounded-md text-xs font-semibold shadow-xs transition">
                Import Excel
            </button>

        </form>

    </div>

</div>

@endsection