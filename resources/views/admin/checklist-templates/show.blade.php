<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Template: {{ $checklistTemplate->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Form untuk menambah item baru --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Tambah Item Checklist Baru</h3>
                    <form method="POST" action="{{ route('admin.checklist-templates.items.store', $checklistTemplate) }}">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <label for="item_description" class="sr-only">Deskripsi Item</label>
                                <input type="text" name="item_description" id="item_description" placeholder="Contoh: Periksa kondisi baut A" class="w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel untuk menampilkan item yang sudah ada --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Daftar Item untuk "{{ $checklistTemplate->title }}"</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($checklistTemplate->items as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $item->item_description }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.template-items.destroy', $item) }}" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">Belum ada item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>