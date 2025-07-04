<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inspection_id',
        'template_item_id',
        'status',
        'notes',
        'photo_url',
        'fail_count',
    ];

    // --- RELATIONS ---

    /**
     * Mendapatkan data inspeksi induk dari hasil ini.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Mendapatkan item checklist spesifik yang berhubungan dengan hasil ini.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(TemplateItem::class, 'template_item_id');
    }
}