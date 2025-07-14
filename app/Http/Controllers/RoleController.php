<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar semua role.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);
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
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Biasanya tidak digunakan untuk manajemen simpel, bisa dikosongkan.
    }

    /**
     * Menampilkan form untuk mengedit role.
     */
    public function edit(Role $role)
    {
        // Hanya mengirim data role, tanpa permissions.
        return view('pages.roles.edit', compact('role'));
    }

    /**
     * Menyimpan perubahan pada role.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        // Hanya update nama dan deskripsi.
        $role->update($request->only('name', 'description'));

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
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