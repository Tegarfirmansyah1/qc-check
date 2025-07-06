@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Laporan Inspeksi #') }}{{ $inspection->id }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            {{-- 👇 PERBAIKI ACTION FORM INI --}}
            <form method="POST" action="{{ route('inspections.update', $inspection) }}">
                @csrf
                @method('PUT')

                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">
                        Mengedit Inspeksi untuk Produk: <span class="font-bold text-blue-600">{{ $inspection->product->name }}</span>
                    </h3>

                    {{-- REKAPITULASI BATCH --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 border rounded-lg">
                        <div>
                            <label for="quantity_total" class="block text-sm font-medium text-gray-700">Jumlah Total Diperiksa</label>
                            <input type="number" name="quantity_total" id="quantity_total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('quantity_total', $inspection->quantity_total) }}" required>
                        </div>
                        <div>
                            <label for="quantity_pass" class="block text-sm font-medium text-gray-700">Jumlah Lulus (Pass)</label>
                            <input type="number" name="quantity_pass" id="quantity_pass" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('quantity_pass', $inspection->quantity_pass) }}" required>
                        </div>
                        <div>
                            <label for="quantity_fail" class="block text-sm font-medium text-gray-700">Jumlah Gagal (Fail)</label>
                            <input type="number" name="quantity_fail" id="quantity_fail" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('quantity_fail', $inspection->quantity_fail) }}" required>
                        </div>
                    </div>

                    {{-- LOG ALASAN KEGAGALAN --}}
                    <h3 class="text-lg font-medium mb-4">Edit Catatan Detail Kegagalan</h3>
                    <div class="space-y-6">
                        @php
                            $resultsByItemId = $inspection->results->keyBy('template_item_id');
                        @endphp

                        @foreach ($inspection->product->checklistTemplates->first()->items as $item)
                            @php
                                $result = $resultsByItemId->get($item->id);
                            @endphp
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold">{{ $item->item_description }}</p>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="fail-count-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Jumlah Gagal Karena Ini:</label>
                                        <input type="number" name="results[{{ $item->id }}][fail_count]" id="fail-count-{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="0" value="{{ old('results.'.$item->id.'.fail_count', $result->fail_count ?? 0) }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="notes-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Catatan:</label>
                                        <input type="text" name="results[{{ $item->id }}][notes]" id="notes-{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('results.'.$item->id.'.notes', $result->notes ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t pt-6 flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('inspections.show', $inspection) }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
