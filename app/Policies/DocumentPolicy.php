<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Pengguna internal (bukan klien) boleh melihat daftar dokumen utama.
        if (!$user->role) return false;
        return strtolower(trim($user->role->name)) !== 'klien eksternal';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        // Admin & Auditor boleh lihat semua.
        if ($user->role && in_array($user->role->name, ['Super Admin', 'Admin Arsip', 'Auditor'])) {
            return true;
        }
        // Pengguna hanya boleh lihat dokumen miliknya sendiri.
        return $user->id === $document->uploaded_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Semua pengguna yang sudah login dan punya role bisa mengunggah.
        // Nanti bisa didetailkan lagi jika perlu.
        return isset($user->role);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        // Hanya Admin atau pemilik dokumen yang boleh.
        if ($user->role && in_array($user->role->name, ['Super Admin', 'Admin Arsip'])) {
            return true;
        }
        return $user->id === $document->uploaded_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        // Hanya Admin atau pemilik dokumen yang boleh.
        if ($user->role && in_array($user->role->name, ['Super Admin', 'Admin Arsip'])) {
            return true;
        }
        return $user->id === $document->uploaded_by;
    }
    
    /**
     * Aturan untuk melihat halaman review dokumen.
     */
    public function reviewAny(User $user): bool
    {
        if (!$user->role) return false;
        return in_array($user->role->name, ['Super Admin', 'Admin Arsip']);
    }
}