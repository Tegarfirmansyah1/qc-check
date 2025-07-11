@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Buat Template Checklist Baru') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('admin.checklist-templates.store') }}">
                    @csrf
                    
                    <!-- Judul Template -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Template</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Produk -->
                    <div class="mb-6">
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Untuk Produk</label>
                        <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Simpan & Lanjut Tambah Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
