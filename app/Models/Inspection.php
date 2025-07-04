<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Inspection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'inspection_date',
        'quantity_total', // Tambahkan ini
        'quantity_pass',  // Tambahkan ini
        'quantity_fail',
    ];

    // --- RELATIONS ---

    /**
     * Mendapatkan user (Staf QC) yang melakukan inspeksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan produk yang diinspeksi.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mendapatkan semua hasil item checklist dari inspeksi ini.
     */
    public function results(): HasMany
    {
        return $this->hasMany(InspectionResult::class);
    }
}