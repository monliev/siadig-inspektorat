<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Ambil semua notifikasi (dibagi per halaman) untuk user yang login
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('pages.notifications.index', compact('notifications'));
    }
}