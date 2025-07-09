@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Riwayat Inspeksi') }}
    </h2>
@endsection

{{-- CSS Kustom untuk Tabel Responsif --}}
@push('styles')
<style>
    /* Terapkan hanya pada layar kecil (misalnya, di bawah 768px) */
    @media (max-width: 767px) {
        /* Sembunyikan header tabel asli */
        .responsive-table thead {
            display: none;
        }
        /* Ubah setiap baris menjadi sebuah kartu */
        .responsive-table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            background-color: #ffffff;
        }
        .responsive-table tr:nth-child(even) {
            background-color: #ffffff; /* Pastikan semua kartu berwarna sama */
        }
        /* Ubah sel menjadi blok vertikal */
        .responsive-table td {
            display: block;
            text-align: right; /* Ratakan konten ke kanan */
            padding-left: 50%; /* Beri ruang untuk label */
            position: relative;
            border-bottom: 1px solid #edf2f7;
        }
        .responsive-table td:last-child {
            border-bottom: none;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        /* Gunakan pseudo-element untuk membuat label dari atribut data-label */
        .responsive-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 0.75rem; /* padding kiri */
            width: 45%;
            padding-right: 0.75rem; /* padding kanan */
            font-weight: 600; /* semibold */
            text-align: left; /* Ratakan label ke kiri */
            color: #4a5568; /* gray-700 */
        }
    }
</style>
@endpush


@section('content')
<div class="py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg rounded-xl">
            <div class="p-4 sm:p-6 text-gray-900">

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Form Pencarian dan Filter --}}
                <form method="GET" action="{{ route('inspections.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-4">
                            <input type="text" name="search" id="search" placeholder="Cari nama produk atau inspektor..." value="{{ $search ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <input type="date" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <input type="date" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Filter</button>
                        <a href="{{ route('inspections.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">Reset</a>
                    </div>
                </form>

                {{-- Tambahkan kelas 'responsive-table' pada tabel --}}
                <table class="min-w-full responsive-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            @if(auth()->user()->role === 'admin')
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Inspektor</th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 md:divide-y-0">
                        @forelse ($inspections as $inspection)
                            <tr>
                                {{-- Tambahkan atribut data-label pada setiap <td> --}}
                                <td data-label="ID" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $inspection->id }}</td>
                                <td data-label="Tanggal" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d M Y, H:i') }}</td>
                                <td data-label="Produk" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inspection->product->name }}</td>
                                @if(auth()->user()->role === 'admin')
                                   <td data-label="Inspektor" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inspection->user->name }}</td>
                                @endif
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                    <a href="{{ route('inspections.show', $inspection) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    @can('update', $inspection)
                                        <a href="{{ route('inspections.edit', $inspection) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    Tidak ada data inspeksi yang cocok dengan filter Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $inspections->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
