<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class DocumentCategoryController extends Controller
{
    public function index()
    {
        $documentCategories = DocumentCategory::latest()->paginate(10);
        return view('pages.document_categories.index', compact('documentCategories'));
    }

    public function create()
    {
        return view('pages.document_categories.create');
    }

    public function store(Request $request)
    {
        // Validasi input, termasuk 'scope'
        $request->validate([
            'name' => 'required|string|max:100|unique:document_categories,name',
            'description' => 'nullable|string',
            'scope' => 'required|in:internal,external',
        ]);

        // Buat data baru dengan 'scope'
        DocumentCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'scope' => $request->scope,
        ]);

        return redirect()->route('document-categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(DocumentCategory $documentCategory)
    {
        return view('pages.document_categories.edit', compact('documentCategory'));
    }

    public function update(Request $request, DocumentCategory $documentCategory)
    {
        // Validasi input, termasuk 'scope'
        $request->validate([
            'name' => 'required|string|max:100|unique:document_categories,name,' . $documentCategory->id,
            'description' => 'nullable|string',
            'scope' => 'required|in:internal,external',
        ]);

        // Update data dengan 'scope'
        $documentCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'scope' => $request->scope,
        ]);

        return redirect()->route('document-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(DocumentCategory $documentCategory)
    {
        $documentCategory->delete();
        return redirect()->route('document-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}