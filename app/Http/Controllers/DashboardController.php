<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionResult;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Query dasar untuk inspeksi, akan difilter berdasarkan peran
        $inspectionsQuery = Inspection::query();
        if ($user->role === 'qc_staff') {
            $inspectionsQuery->where('user_id', $user->id);
        }

        // --- DATA UNTUK KARTU STATISTIK ---

        // 1. Total Inspeksi (Bulan Ini)
        $totalInspectionsThisMonth = (clone $inspectionsQuery)
            ->whereYear('inspection_date', $now->year)
            ->whereMonth('inspection_date', $now->month)
            ->count();

        // 2. Tingkat Lulus & Data Grafik Donat
        $totalPass = (clone $inspectionsQuery)->sum('quantity_pass');
        $totalFail = (clone $inspectionsQuery)->sum('quantity_fail');
        $totalChecked = $totalPass + $totalFail;
        $passRate = ($totalChecked > 0) ? round(($totalPass / $totalChecked) * 100, 1) : 0;
        
        $donutChartData = [
            'labels' => ['Lulus', 'Gagal'],
            'data' => [$totalPass, $totalFail],
        ];

        // 3. Total Item Gagal (berdasarkan kuantitas)
        $totalItemsFailed = $totalFail;

        // 4. Total Produk (ini data global, sama untuk semua)
        $totalProducts = Product::count();


        // --- DATA UNTUK GRAFIK BATANG ---
        
        // Query dasar untuk hasil inspeksi, difilter berdasarkan peran
        $resultsQuery = InspectionResult::query()
            ->join('inspections', 'inspection_results.inspection_id', '=', 'inspections.id')
            ->join('template_items', 'inspection_results.template_item_id', '=', 'template_items.id')
            ->select(
                'template_items.item_description',
                DB::raw('SUM(inspection_results.fail_count) as total_failed')
            )
            ->groupBy('template_items.item_description')
            ->orderBy('total_failed', 'desc')
            ->limit(5);

        if ($user->role === 'qc_staff') {
            $resultsQuery->where('inspections.user_id', $user->id);
        }

        $topFailedItems = $resultsQuery->get();
        
        $barChartData = [
            'labels' => $topFailedItems->pluck('item_description'),
            'data' => $topFailedItems->pluck('total_failed'),
        ];


        return view('dashboard', compact(
            'totalInspectionsThisMonth',
            'passRate',
            'totalItemsFailed',
            'totalProducts',
            'donutChartData',
            'barChartData'
        ));
    }
}
