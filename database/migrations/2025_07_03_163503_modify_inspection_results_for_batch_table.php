<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inspection_results', function (Blueprint $table) {
            $table->dropColumn('status'); // Hapus kolom status Pass/Fail
            $table->integer('fail_count')->after('template_item_id'); // Tambah kolom untuk jumlah gagal per item
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
