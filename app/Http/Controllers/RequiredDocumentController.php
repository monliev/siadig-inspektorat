<?php

namespace App\Http\Controllers;

use App\Models\RequiredDocument;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    public function index()
    {
        $requiredDocuments = RequiredDocument::latest()->paginate(10);
        return view('pages.required-documents.index', compact('requiredDocuments'));
    }

    public function create()
    {
        return view('pages.required-documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_type' => 'required|string',
        ]);

        RequiredDocument::create($request->all());

        return redirect()->route('required-documents.index')->with('success', 'Dokumen persyaratan berhasil ditambahkan.');
    }

    public function edit(RequiredDocument $requiredDocument)
    {
        return view('pages.required-documents.edit', compact('requiredDocument'));
    }

    public function update(Request $request, RequiredDocument $requiredDocument)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_type' => 'required|string',
        ]);

        $requiredDocument->update($request->all());

        return redirect()->route('required-documents.index')->with('success', 'Dokumen persyaratan berhasil diperbarui.');
    }

    public function destroy(RequiredDocument $requiredDocument)
    {
        $requiredDocument->delete();
        return redirect()->route('required-documents.index')->with('success', 'Dokumen persyaratan berhasil dihapus.');
    }
}