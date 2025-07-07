{{-- File ini hanya berisi tabel HTML sederhana untuk di-render menjadi Excel --}}
<table>
    <thead>
        <tr>
            <th colspan="4" style="font-size: 18px; font-weight: bold;">Laporan Hasil Inspeksi Kualitas</th>
        </tr>
        <tr>
            <th colspan="4">QC-Check System</th>
        </tr>
        <tr></tr> {{-- Baris kosong sebagai spasi --}}
    </thead>
    <tbody>
        <tr></tr>
        <tr>
            <td style="font-weight: bold;" colspan="4">Informasi Umum</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">ID Inspeksi</td>
            <td colspan="3">#{{ $inspection->id }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Produk</td>
            <td colspan="3">{{ $inspection->product->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Inspektor</td>
            <td colspan="3">{{ $inspection->user->name }} (NIK: {{ $inspection->user->nik }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tanggal Inspeksi</td>
            <td colspan="3">{{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d F Y, H:i') }}</td>
        </tr>
        <tr></tr> {{-- Baris kosong sebagai spasi --}}

        <tr>
            <td style="font-weight: bold;" colspan="4">Rekapitulasi Batch</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Total Diperiksa</td>
            <td style="font-weight: bold;">Lulus (Pass)</td>
            <td style="font-weight: bold;">Gagal (Fail)</td>
            <td></td>
        </tr>
        <tr>
            <td>{{ $inspection->quantity_total }}</td>
            <td>{{ $inspection->quantity_pass }}</td>
            <td>{{ $inspection->quantity_fail }}</td>
            <td></td>
        </tr>
        <tr></tr> {{-- Baris kosong sebagai spasi --}}
        
        <tr>
            <td style="font-weight: bold;" colspan="4">Rincian Kegagalan</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Item Checklist</td>
            <td style="font-weight: bold;">Jumlah Gagal</td>
            <td style="font-weight: bold;" colspan="2">Catatan</td>
        </tr>
        @forelse ($inspection->results as $result)
            <tr>
                <td>{{ $result->item->item_description }}</td>
                <td>{{ $result->fail_count }}</td>
                <td colspan="2">{{ $result->notes ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Tidak ada catatan kegagalan untuk inspeksi ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
