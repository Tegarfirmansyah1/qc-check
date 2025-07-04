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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom NIK. Jadikan nullable() dulu agar migrasi bisa berjalan di tabel yang sudah ada data.
            $table->string('nik')->unique()->nullable()->after('name');

            // Jadikan kolom email opsional (nullable) dan tidak lagi unik
            $table->string('email')->nullable()->unique(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan perubahan jika migrasi di-rollback
            $table->dropColumn('nik');
            $table->string('email')->nullable(false)->unique()->change();
        });
    }
};
