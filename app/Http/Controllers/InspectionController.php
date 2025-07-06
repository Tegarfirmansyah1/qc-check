<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionResult;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InspectionController extends Controller
{
    // Menggunakan trait agar method $this->authorize() tersedia
    use AuthorizesRequests;

    // Method __construct() dihapus untuk menghindari error

    /**
     * Menampilkan daftar inspeksi.
     */
    public function index()
    {
        // Otorisasi manual untuk melihat daftar
        $this->authorize('viewAny', Inspection::class);
        
        $query = Inspection::with(['user', 'product'])->latest();

        if (auth()->user()->role === 'qc_staff') {
            $query->where('user_id', auth()->id());
        }

        $inspections = $query->get();

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Menampilkan form untuk membuat inspeksi baru.
     */
    public function create(Request $request)
    {
        // Otorisasi manual untuk membuat
        $this->authorize('create', Inspection::class);

        $products = Product::all();
        $selectedProduct = null;
        
        if ($request->has('product_id')) {
            $selectedProduct = Product::with('checklistTemplates.items')->find($request->product_id);
        }

        return view('inspections.create', compact('products', 'selectedProduct'));
    }

    /**
     * Menyimpan inspeksi baru.
     */
    public function store(Request $request)
    {
        // Otorisasi manual untuk menyimpan (menggunakan hak 'create')
        $this->authorize('create', Inspection::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_total' => 'required|integer|min:0',
            'quantity_pass' => 'required|integer|min:0',
            'quantity_fail' => 'required|integer|min:0',
            'results' => 'nullable|array',
        ]);

        $inspection = Inspection::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity_total' => $request->quantity_total,
            'quantity_pass' => $request->quantity_pass,
            'quantity_fail' => $request->quantity_fail,
            'inspection_date' => now(),
        ]);

        if ($request->has('results')) {
            foreach ($request->results as $templateItemId => $result) {
                if (isset($result['fail_count']) && $result['fail_count'] > 0) {
                    InspectionResult::create([
                        'inspection_id' => $inspection->id,
                        'template_item_id' => $templateItemId,
                        'fail_count' => $result['fail_count'],
                        'notes' => $result['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('inspections.index')->with('success', 'Laporan inspeksi batch berhasil disimpan!');
    }

    /**
     * Menampilkan detail satu inspeksi.
     */
    public function show(Inspection $inspection)
    {
        // Otorisasi manual untuk melihat detail
        $this->authorize('view', $inspection);
        
        $inspection->load(['user', 'product', 'results.item']);
        return view('inspections.show', compact('inspection'));
    }

    /**
     * Menampilkan form untuk mengedit inspeksi.
     */
    public function edit(Inspection $inspection)
    {
        // Otorisasi manual untuk menampilkan form edit
        $this->authorize('update', $inspection);

        $inspection->load('product.checklistTemplates.items', 'results');
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Memperbarui data inspeksi di database.
     */
    public function update(Request $request, Inspection $inspection)
    {
        // Otorisasi manual untuk memproses update
        $this->authorize('update', $inspection);

        $validatedData = $request->validate([
            'quantity_total' => 'required|integer|min:0',
            'quantity_pass' => 'required|integer|min:0',
            'quantity_fail' => 'required|integer|min:0',
            'results' => 'nullable|array',
        ]);

        $inspection->update($validatedData);

        $inspection->results()->delete();

        if ($request->has('results')) {
            foreach ($request->results as $templateItemId => $result) {
                if (isset($result['fail_count']) && $result['fail_count'] > 0) {
                    InspectionResult::create([
                        'inspection_id' => $inspection->id,
                        'template_item_id' => $templateItemId,
                        'fail_count' => $result['fail_count'],
                        'notes' => $result['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('inspections.show', $inspection)->with('success', 'Inspeksi berhasil diperbarui!');
    }

    /**
     * Menghapus data inspeksi.
     */
    public function destroy(Inspection $inspection)
    {
        // Otorisasi manual untuk menghapus
        $this->authorize('delete', $inspection);

        $inspection->delete();
        return redirect()->route('inspections.index')->with('success', 'Inspeksi berhasil dihapus!');
    }
}
