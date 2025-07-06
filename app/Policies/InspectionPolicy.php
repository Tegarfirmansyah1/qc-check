<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InspectionPolicy
{
    /**
     * Menentukan apakah pengguna bisa melihat daftar inspeksi.
     */
    public function viewAny(User $user): bool
    {
        // Semua pengguna yang sudah login boleh melihat halaman riwayat.
        return true;
    }

    /**
     * Menentukan apakah pengguna bisa melihat detail inspeksi tertentu.
     */
    public function view(User $user, Inspection $inspection): bool
    {
        // Admin bisa melihat detail inspeksi apa pun.
        if ($user->role === 'admin') {
            return true;
        }
        // Staf hanya bisa melihat detail inspeksi miliknya sendiri.
        return $user->id === $inspection->user_id;
    }

    /**
     * Menentukan apakah pengguna bisa membuat inspeksi baru.
     */
    public function create(User $user): bool
    {
        // Semua pengguna yang sudah login boleh membuat inspeksi baru.
        return true;
    }

    /**
     * Menentukan apakah pengguna bisa memperbarui data inspeksi.
     */
    public function update(User $user, Inspection $inspection): bool
    {
        // Admin bisa mengedit inspeksi apa pun.
        if ($user->role === 'admin') {
            return true;
        }
        // Staf hanya bisa mengedit inspeksi miliknya sendiri.
        return $user->id === $inspection->user_id;
    }

    /**
     * Menentukan apakah pengguna bisa menghapus data inspeksi.
     */
    public function delete(User $user, Inspection $inspection): bool
    {
        // Hanya admin yang bisa menghapus.
        return $user->role === 'admin';
    }
}
