<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Entity; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::with('role', 'entity')->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(10);

        return view('pages.users.index', compact('users', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        $entities = Entity::with('parent')->orderBy('name')->get();
        return view('pages.users.create', compact('roles', 'entities')); // Kirim ke view
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'entity_id' => 'nullable|exists:entities,id',
            'nip' => 'nullable|string|max:20|unique:users,nip',
            'jabatan' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'entity_id' => $request->entity_id,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'password' => Hash::make('TrenggalekMeroket#!'),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $entities = Entity::with('parent')->orderBy('name')->get();
        return view('pages.users.edit', compact('user', 'roles', 'entities')); // Kirim ke view
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'entity_id' => 'nullable|exists:entities,id', 
            'nip' => 'nullable|string|max:20|unique:users,nip,' . $user->id,
            'jabatan' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'entity_id' => $request->entity_id,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Pastikan pengguna tidak menghapus akunnya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}