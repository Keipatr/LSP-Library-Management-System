<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index(Request $request)
    {
        // Query dasar
        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        } // Jika tidak ada filter role, tampilkan semua role

        // Ambil data user dengan pagination
        $users = $query->where('status_delete', '0')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:0,1',
        ]);

        try {
            // Buat user baru
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status_delete' => '0',
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan user');
        }
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user_id, 'user_id'), 
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:0,1',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // Update data user
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat mengupdate user');
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            // Soft delete dengan mengubah status_delete
            $user->status_delete = '1';
            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus user');
        }
    }
}
