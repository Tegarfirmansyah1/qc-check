<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data user lama untuk menghindari duplikasi NIK jika seeder dijalankan lagi
        User::truncate();

        // Buat Akun Admin
        User::create([
            'name' => 'Admin QC',
            'nik' => 'ADMIN001', // Menggunakan NIK sebagai login
            'email' => 'admin@example.com', // Email tetap diisi (opsional, bisa null)
            'password' => Hash::make('admin122'),
            'role' => 'admin',
        ]);

        // Buat Akun Staf QC
        User::create([
            'name' => 'Maman Tajudin',
            'nik' => 'QCSTAFF001', // Menggunakan NIK sebagai login
            'email' => 'maman@example.com', // Email tetap diisi (opsional, bisa null)
            'password' => Hash::make('maman122'),
            'role' => 'qc_staff',
        ]);
    }
}
