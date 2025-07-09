@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Inspeksi') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- ... (form pemilihan produk) ... --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 rounded-xl">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium mb-4">Langkah 1: Pilih Produk</h3>
                <form method="GET" action="{{ route('inspections.create') }}">
                    <div class="flex items-center space-x-4">
                        <div class="flex-grow">
                            <select name="product_id" id="product_id" class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $selectedProduct->id ?? '') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Mulai
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($selectedProduct && $selectedProduct->checklistTemplates->isNotEmpty())
            @php
                $template = $selectedProduct->checklistTemplates->first();
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">

                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Langkah 2: Isi Detail Inspeksi untuk "{{ $selectedProduct->name }}"</h3>

                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Terjadi Kesalahan</p>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 border rounded-lg">
                            <div>
                                <label for="quantity_total" class="block text-sm font-medium text-gray-700">Jumlah Total Diperiksa</label>
                                <input type="number" name="quantity_total" id="quantity_total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('quantity_total') }}" required>
                            </div>
                            <div>
                                <label for="quantity_pass" class="block text-sm font-medium text-gray-700">Jumlah Lulus (Pass)</label>
                                <input type="number" name="quantity_pass" id="quantity_pass" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('quantity_pass') }}" required>
                            </div>
                            <div>
                                <label for="quantity_fail" class="block text-sm font-medium text-gray-700">Jumlah Gagal (Fail)</label>
                                <input type="number" name="quantity_fail" id="quantity_fail" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('quantity_fail') }}" required>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-4">Langkah 3: Catat Detail Kegagalan (Jika Ada)</h3>
                        <div class="space-y-6">
                            @foreach ($template->items as $item)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-semibold">{{ $item->item_description }}</p>
                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="fail-count-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Jumlah Gagal Karena Ini:</label>
                                            <input type="number" name="results[{{ $item->id }}][fail_count]" id="fail-count-{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="0" value="{{ old('results.'.$item->id.'.fail_count') }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label for="notes-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Catatan:</label>
                                            <input type="text" name="results[{{ $item->id }}][notes]" id="notes-{{ $item->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('results.'.$item->id.'.notes') }}">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="photo-{{ $item->id }}" class="block text-sm font-medium text-gray-700">Upload Foto Bukti (Jika Perlu):</label>
                                        <input type="file" name="results[{{ $item->id }}][photo]" id="photo-{{ $item->id }}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 border-t pt-6">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-lg">
                                Simpan Laporan Inspeksi Batch
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
