<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\TemplateItem;
use Illuminate\Http\Request;

class TemplateItemController extends Controller
{
    public function store(Request $request, ChecklistTemplate $checklistTemplate)
    {
        $validated = $request->validate([
            'item_description' => 'required|string|max:255',
        ]);

        // Buat item baru yang langsung berelasi dengan template-nya
        $checklistTemplate->items()->create($validated);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function destroy(TemplateItem $templateItem)
    {
        $templateItem->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}