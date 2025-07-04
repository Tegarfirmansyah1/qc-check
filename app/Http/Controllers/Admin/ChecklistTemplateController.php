<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\Product; // <-- Jangan lupa import
use Illuminate\Http\Request;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua produk untuk ditampilkan di dropdown
        $products = Product::all();
        return view('admin.checklist-templates.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $template = ChecklistTemplate::create($validated);

        // Alihkan ke halaman 'show' agar admin bisa langsung menambah item
        return redirect()->route('admin.checklist-templates.show', $template)
                         ->with('success', 'Template berhasil dibuat. Silakan tambahkan item checklist.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChecklistTemplate $checklistTemplate)
    {
        // Load relasi 'items' dan 'product' untuk ditampilkan di view
        $checklistTemplate->load('items', 'product');
        return view('admin.checklist-templates.show', compact('checklistTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
