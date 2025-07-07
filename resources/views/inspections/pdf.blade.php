<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Inspeksi #{{ $inspection->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .section-title { font-size: 16px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px;}
        .info-grid { display: block; margin-bottom: 20px; }
        .info-grid::after { content: ""; clear: both; display: table; }
        .info-grid .info-item { float: left; width: 48%; margin-right: 2%; }
        .recap-grid { display: block; }
        .recap-grid::after { content: ""; clear: both; display: table; }
        .recap-grid .recap-item { float: left; width: 32%; margin-right: 1%; text-align: center; border: 1px solid #ddd; padding: 10px; }
        .recap-item .value { font-size: 20px; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #777; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Hasil Inspeksi Kualitas</h1>
            <p>QC-Check System</p>
        </div>

        <div class="section-title">Informasi Umum</div>
        <table>
            <tr>
                <th width="30%">ID Inspeksi</th>
                <td>#{{ $inspection->id }}</td>
                <th width="30%">Nama Produk</th>
                <td>{{ $inspection->product->name }}</td>
            </tr>
            <tr>
                <th>Tanggal Inspeksi</th>
                <td>{{ \Carbon\Carbon::parse($inspection->inspection_date)->format('d F Y, H:i') }}</td>
                <th>Nama Inspektor</th>
                <td>{{ $inspection->user->name }} (NIK: {{ $inspection->user->nik }})</td>
            </tr>
        </table>

        <div class="section-title">Rekapitulasi Batch</div>
        <div class="recap-grid">
            <div class="recap-item">
                <div class="value">{{ $inspection->quantity_total }}</div>
                <div>Total Diperiksa</div>
            </div>
            <div class="recap-item">
                <div class="value">{{ $inspection->quantity_pass }}</div>
                <div>Lulus (Pass)</div>
            </div>
            <div class="recap-item">
                <div class="value">{{ $inspection->quantity_fail }}</div>
                <div>Gagal (Fail)</div>
            </div>
        </div>

        <div class="section-title">Rincian Kegagalan</div>
        @if($inspection->results->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Item Checklist</th>
                        <th>Jumlah Gagal</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inspection->results as $result)
                        <tr>
                            <td>{{ $result->item->item_description }}</td>
                            <td>{{ $result->fail_count }}</td>
                            <td>{{ $result->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada catatan kegagalan untuk inspeksi ini.</p>
        @endif

        <div class="footer">
            Dokumen ini dibuat secara otomatis oleh sistem QC-Check pada {{ now()->format('d F Y, H:i') }}.
        </div>
    </div>
</body>
</html>
