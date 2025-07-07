<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionResult;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Rules\SumEqualsTotal;
use App\Rules\SumOfResultsEqualsFail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InspectionExport;

class InspectionController extends Controller
{
    // Menggunakan trait agar method $this->authorize() tersedia
    use AuthorizesRequests;

    /**
     * Menampilkan daftar inspeksi dengan fitur pencarian dan filter.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Inspection::class);
        
        $query = Inspection::with(['user', 'product'])->latest();

        if (auth()->user()->role === 'qc_staff') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('inspection_date', [$startDate, $endDate]);
        }

        $inspections = $query->paginate(15)->withQueryString();

        return view('inspections.index', [
            'inspections' => $inspections,
            'search' => $request->input('search'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    }

    /**
     * Menampilkan form untuk membuat inspeksi baru.
     */
    public function create(Request $request)
    {
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
        $this->authorize('create', Inspection::class);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_total' => ['required', 'integer', 'min:0'],
            'quantity_pass' => ['required', 'integer', 'min:0'],
            'quantity_fail' => ['required', 'integer', 'min:0', new SumEqualsTotal($request)],
            'results' => ['nullable', 'array', new SumOfResultsEqualsFail($request)],
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
        $this->authorize('view', $inspection);
        
        $inspection->load(['user', 'product', 'results.item']);
        return view('inspections.show', compact('inspection'));
    }

    /**
     * Menampilkan form untuk mengedit inspeksi.
     */
    public function edit(Inspection $inspection)
    {
        $this->authorize('update', $inspection);

        $inspection->load('product.checklistTemplates.items', 'results');
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Memperbarui data inspeksi di database.
     */
    public function update(Request $request, Inspection $inspection)
    {
        $this->authorize('update', $inspection);

        $validatedData = $request->validate([
            'quantity_total' => ['required', 'integer', 'min:0'],
            'quantity_pass' => ['required', 'integer', 'min:0'],
            'quantity_fail' => ['required', 'integer', 'min:0', new SumEqualsTotal($request)],
            'results' => ['nullable', 'array', new SumOfResultsEqualsFail($request)],
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
        $this->authorize('delete', $inspection);

        $inspection->delete();
        return redirect()->route('inspections.index')->with('success', 'Inspeksi berhasil dihapus!');
    }

    /**
     * Membuat dan mengunduh laporan inspeksi dalam format PDF.
     */
    public function downloadPDF(Inspection $inspection)
    {
        $this->authorize('view', $inspection);

        $inspection->load(['user', 'product', 'results.item']);
        $pdf = Pdf::loadView('inspections.pdf', compact('inspection'));
        $fileName = 'Laporan-Inspeksi-' . $inspection->id . '-' . $inspection->product->name . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Membuat dan mengunduh laporan inspeksi dalam format Excel.
     */
    public function exportExcel(Inspection $inspection)
    {
        $this->authorize('view', $inspection);

        $fileName = 'Laporan-Inspeksi-' . $inspection->id . '-' . str_replace(' ', '-', $inspection->product->name) . '.xlsx';

        return Excel::download(new InspectionExport($inspection), $fileName);
    }
}
