<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\Product;
use Illuminate\Http\Request;

class ChecklistTemplateController extends Controller
{
    /**
     * Menampilkan daftar semua template checklist.
     */
    public function index()
    {
        $checklistTemplates = ChecklistTemplate::with('product')->latest()->get();
        return view('admin.checklist-templates.index', compact('checklistTemplates'));
    }

    /**
     * Menampilkan form untuk membuat template baru.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.checklist-templates.create', compact('products'));
    }

    /**
     * Menyimpan template baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $template = ChecklistTemplate::create($validated);

        return redirect()->route('admin.checklist-templates.show', $template)
                         ->with('success', 'Template berhasil dibuat. Silakan tambahkan item checklist.');
    }

    /**
     * Menampilkan detail template beserta item-itemnya.
     */
    public function show(ChecklistTemplate $checklistTemplate)
    {
        $checklistTemplate->load('items', 'product');
        return view('admin.checklist-templates.show', compact('checklistTemplate'));
    }

    /**
     * Menampilkan form untuk mengedit template.
     */
    public function edit(ChecklistTemplate $checklistTemplate)
    {
        $products = Product::all();
        return view('admin.checklist-templates.edit', compact('checklistTemplate', 'products'));
    }

    /**
     * Memperbarui template di database.
     */
    public function update(Request $request, ChecklistTemplate $checklistTemplate)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $checklistTemplate->update($validated);

        return redirect()->route('admin.checklist-templates.index')
                         ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Menghapus template dari database.
     */
    public function destroy(ChecklistTemplate $checklistTemplate)
    {
        $checklistTemplate->delete();
        return redirect()->route('admin.checklist-templates.index')
                         ->with('success', 'Template berhasil dihapus.');
    }
}
