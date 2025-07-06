<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ... (method index, create, store, edit, update biarkan seperti semula)

    public function index()
    {
        $users = User::where('role', 'qc_staff')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
            'role' => 'qc_staff',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'nik' => $request->nik,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna (sebaiknya tidak digunakan, diganti dengan toggleStatus).
     */
    public function destroy(User $user)
    {
        // Sebaiknya kosongkan atau beri pesan error untuk mencegah penghapusan
        return back()->with('error', 'Penghapusan pengguna tidak diizinkan. Gunakan fitur nonaktifkan.');
    }

    /**
     * Method baru untuk mengubah status aktif/nonaktif pengguna.
     */
    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $message = $user->is_active ? 'Pengguna berhasil diaktifkan kembali.' : 'Pengguna berhasil dinonaktifkan.';

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
