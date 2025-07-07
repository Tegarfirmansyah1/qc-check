<?php

namespace App\Exports;

use App\Models\Inspection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspectionExport implements FromView, WithTitle, WithColumnWidths, WithEvents
{
    protected $inspection;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
    }

    public function view(): View
    {
        return view('inspections.excel', [
            'inspection' => $this->inspection->load(['user', 'product', 'results.item'])
        ]);
    }

    public function title(): string
    {
        return 'Laporan Inspeksi #' . $this->inspection->id;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 15,
            'D' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- STYLING UMUM ---
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('A2:D2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // --- STYLING SECTION ---
                $sheet->getStyle('A5')->getFont()->setBold(true);
                $sheet->getStyle('A11')->getFont()->setBold(true);
                $sheet->getStyle('A15')->getFont()->setBold(true);

                // Header Tabel
                $sheet->getStyle('A12:C12')->getFont()->setBold(true);
                $sheet->getStyle('A16:D16')->getFont()->setBold(true);
                
                // --- BORDERS ---
                $lastRow = $sheet->getHighestRow();
                // Border untuk "Informasi Umum" - Diperbaiki dari B9 menjadi D9
                $sheet->getStyle('A6:D9')->getBorders()->getAllBorders()->setBorderStyle('thin');
                // Border untuk "Rekapitulasi Batch"
                $sheet->getStyle('A12:C13')->getBorders()->getAllBorders()->setBorderStyle('thin');
                // Border untuk "Rincian Kegagalan"
                if ($lastRow >= 16) {
                    $sheet->getStyle('A16:D' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle('thin');
                }
            },
        ];
    }
}
