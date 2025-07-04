<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\Admin\ChecklistTemplateController;
use App\Http\Controllers\Admin\TemplateItemController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index'); 
    Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
    Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
    // (Nantinya kita akan tambah route untuk riwayat di sini)
    // routes/web.php di dalam grup middleware 'auth'
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('checklist-templates', ChecklistTemplateController::class);
    Route::post('checklist-templates/{checklist_template}/items', [TemplateItemController::class, 'store'])->name('checklist-templates.items.store');
    Route::delete('template-items/{template_item}', [TemplateItemController::class, 'destroy'])->name('template-items.destroy');
});

if (App::isLocal()) {
    Route::get('/hash-tool', function () {
        return view('dev.hash_tool');
    })->name('dev.hash_tool');

    Route::post('/hash-tool', function (Request $request) {
        $request->validate(['password' => 'required|string']);
        $hashedPassword = Hash::make($request->password);
        return back()->with(['original_password' => $request->password, 'hashed_password' => $hashedPassword]);
    })->name('dev.hash_tool.submit');
}

require __DIR__.'/auth.php';
