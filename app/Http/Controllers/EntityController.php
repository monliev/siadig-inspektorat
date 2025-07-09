<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Entity::with('parent')->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('parent', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $entities = $query->paginate(15);
        
        // Kirim juga variabel $search ke view
        return view('pages.entities.index', compact('entities', 'search'));
    }

    public function create()
    {
        // Ambil hanya entitas yang bisa menjadi induk (OPD & Kecamatan)
        $parentEntities = Entity::whereIn('type', ['OPD', 'Kecamatan'])->orderBy('name')->get();
        return view('pages.entities.create', compact('parentEntities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:OPD,Kecamatan,Desa',
            'parent_id' => 'nullable|exists:entities,id',
            'agency_code' => 'nullable|string|max:255|unique:entities,agency_code', // <-- Tambahkan ini
        ]);

        Entity::create($request->all());
        return redirect()->route('entities.index')->with('success', 'Entitas baru berhasil ditambahkan.');
    }

    public function edit(Entity $entity)
    {
        $parentEntities = Entity::whereIn('type', ['OPD', 'Kecamatan'])->where('id', '!=', $entity->id)->orderBy('name')->get();
        return view('pages.entities.edit', compact('entity', 'parentEntities'));
    }

     public function update(Request $request, Entity $entity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:OPD,Kecamatan,Desa',
            'parent_id' => 'nullable|exists:entities,id',
            'agency_code' => 'nullable|string|max:255|unique:entities,agency_code,' . $entity->id, // <-- Tambahkan ini
        ]);

        $entity->update($request->all());
        return redirect()->route('entities.index')->with('success', 'Entitas berhasil diperbarui.');
    }

    public function destroy(Entity $entity)
    {
        $entity->delete();
        return redirect()->route('entities.index')->with('success', 'Entitas berhasil dihapus.');
    }
}