<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\InspectionResult;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
     public function index()
    {
        $query = Inspection::with(['user', 'product'])->latest();

        // Jika yang login adalah staf QC, hanya tampilkan inspeksi miliknya
        if (auth()->user()->role === 'qc_staff') {
            $query->where('user_id', auth()->id());
        }

        $inspections = $query->get();

        return view('inspections.index', compact('inspections'));
    }
    public function create(Request $request)
    {
        // Ambil semua produk untuk ditampilkan di dropdown
        $products = Product::all();
        $selectedProduct = null;
        
        // Cek apakah ada request 'product_id' (artinya user sudah memilih produk)
        if ($request->has('product_id')) {
            // Cari produk yang dipilih, beserta template dan item-itemnya
            $selectedProduct = Product::with('checklistTemplates.items')->find($request->product_id);
        }

        return view('inspections.create', compact('products', 'selectedProduct'));
    }

    public function show(Inspection $inspection)
    {
        // Gunakan eager loading untuk mengambil semua data terkait secara efisien
        $inspection->load(['user', 'product', 'results.item']);

        return view('inspections.show', compact('inspection'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input rekapitulasi dan dasar
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_total' => 'required|integer|min:0',
            'quantity_pass' => 'required|integer|min:0',
            'quantity_fail' => 'required|integer|min:0',
            'results' => 'nullable|array',
        ]);

        // 2. Buat record Inspeksi utama dengan data rekapitulasi
        $inspection = Inspection::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity_total' => $request->quantity_total,
            'quantity_pass' => $request->quantity_pass,
            'quantity_fail' => $request->quantity_fail,
            'inspection_date' => now(),
        ]);

        // 3. Loop melalui detail kegagalan dan simpan HANYA JIKA ADA YANG GAGAL
        if ($request->has('results')) {
            foreach ($request->results as $templateItemId => $result) {
                // Hanya proses dan simpan jika jumlah gagal untuk item ini lebih dari 0
                if (isset($result['fail_count']) && $result['fail_count'] > 0) {
                    $photoPath = null;
                    // Cek jika ada file foto yang di-upload
                    if (isset($result['photo']) && $result['photo']->isValid()) {
                        $photoPath = $result['photo']->store('inspection-photos', 'public');
                    }
                    
                    InspectionResult::create([
                        'inspection_id' => $inspection->id,
                        'template_item_id' => $templateItemId,
                        'fail_count' => $result['fail_count'],
                        'notes' => $result['notes'] ?? null,
                        'photo_url' => $photoPath,
                    ]);
                }
            }
        }

        // 4. Redirect ke halaman riwayat dengan pesan sukses
        return redirect()->route('inspections.index')->with('success', 'Laporan inspeksi batch berhasil disimpan!');
    }
}