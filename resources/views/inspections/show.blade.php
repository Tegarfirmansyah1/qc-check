@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Inspeksi #') }}{{ $inspection->id }}
        </h2>
        
        {{-- Tombol Aksi, ditampilkan berdasarkan hak akses dari Policy --}}
        <div class="flex items-center space-x-2">
            @can('update', $inspection)
                <a href="{{ route('inspections.edit', $inspection) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                    Edit
                </a>
            @endcan
            @can('delete', $inspection)
                <form action="{{ route('inspections.destroy', $inspection) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data inspeksi ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        {{-- Informasi Umum & Rekapitulasi Batch --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium border-b pb-3 mb-4">Informasi Umum</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">ID Inspeksi</dt>
                        <dd class="mt-1 text-gray-900">#{{ $inspection->id }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Tanggal</dt>
                        <dd class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Nama Produk</dt>
                        <dd class="mt-1 text-gray-900">{{ $inspection->product->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Inspektor</dt>
                        <dd class="mt-1 text-gray-900">{{ $inspection->user->name }}</dd>
                    </div>
                </div>

                <h3 class="text-lg font-medium border-b pb-3 mt-8 mb-4">Rekapitulasi Batch</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="font-bold text-2xl text-blue-800">{{ $inspection->quantity_total }}</dt>
                        <dd class="mt-1 text-sm font-medium text-blue-600">Total Diperiksa</dd>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <dt class="font-bold text-2xl text-green-800">{{ $inspection->quantity_pass }}</dt>
                        <dd class="mt-1 text-sm font-medium text-green-600">Lulus (Pass)</dd>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <dt class="font-bold text-2xl text-red-800">{{ $inspection->quantity_fail }}</dt>
                        <dd class="mt-1 text-sm font-medium text-red-600">Gagal (Fail)</dd>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rincian Kegagalan --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium mb-4">Rincian Kegagalan</h3>
                <div class="space-y-4">
                    @forelse ($inspection->results as $result)
                        <div class="border rounded-lg p-4">
                            <p class="font-semibold">{{ $result->item->item_description }}</p>
                            <div class="mt-2 pl-4 border-l-2 border-gray-200 text-sm">
                                <p><strong>Jumlah Gagal:</strong> {{ $result->fail_count }}</p>
                                @if($result->notes)
                                    <p><strong>Catatan:</strong> {{ $result->notes }}</p>
                                @endif
                                @if($result->photo_url)
                                    <p class="mt-2"><strong>Bukti Foto:</strong></p>
                                    <a href="{{ asset('storage/' . $result->photo_url) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $result->photo_url) }}" alt="Bukti Foto" class="mt-1 rounded-md max-w-xs max-h-48 object-cover">
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">Tidak ada catatan kegagalan untuk inspeksi ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
