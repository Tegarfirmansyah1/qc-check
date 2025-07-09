@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Pengguna') }}
    </h2>
@endsection

@push('styles')
<style>
    @media (max-width: 767px) {
        .responsive-table thead { display: none; }
        .responsive-table tr { display: block; margin-bottom: 1rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); background-color: #ffffff; }
        .responsive-table td { display: block; text-align: right; padding-left: 50%; position: relative; border-bottom: 1px solid #edf2f7; }
        .responsive-table td:last-child { border-bottom: none; padding-top: 1rem; padding-bottom: 1rem; }
        .responsive-table td::before { content: attr(data-label); position: absolute; left: 0.75rem; width: 45%; padding-right: 0.75rem; font-weight: 600; text-align: left; color: #4a5568; }
    }
</style>
@endpush

@section('content')
<div class="py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg rounded-xl">
            <div class="p-4 sm:p-6 text-gray-900">
                
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <div class="mb-6">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        + Tambah Pengguna Baru
                    </a>
                </div>

                <table class="min-w-full responsive-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran (Role)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 md:divide-y-0">
                        @forelse ($users as $user)
                            <tr>
                                <td data-label="Nama" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td data-label="NIK" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->nik }}</td>
                                <td data-label="Peran" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $user->role) }}</td>
                                <td data-label="Status" class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                    @endif
                                </td>
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form class="inline-block" action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengubah status pengguna ini?');">
                                        @csrf
                                        @method('PATCH')
                                        @if($user->is_active)
                                            <button type="submit" class="text-red-600 hover:text-red-900">Nonaktifkan</button>
                                        @else
                                            <button type="submit" class="text-green-600 hover:text-green-900">Aktifkan</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    Belum ada data pengguna staf.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
