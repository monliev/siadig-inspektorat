<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar semua role.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10); // Diubah agar lebih rapi dengan paginasi
        return view('pages.roles.index', compact('roles'));
    }

    /**
     * Menampilkan form untuk membuat role baru.
     */
    public function create()
    {
        return view('pages.roles.create');
    }

    /**
     * Menyimpan role baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        // Simpan data baru
        Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'web' // <-- TAMBAHKAN BARIS INI
        ]);

        // Kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Tidak digunakan saat ini
    }

    /**
     * Menampilkan form untuk mengedit role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update($request->only('name', 'description'));
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'Role dan hak akses berhasil diperbarui.');
    }

    /**
     * Menghapus role dari database.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}