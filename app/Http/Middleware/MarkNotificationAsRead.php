<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika ada parameter 'read' di URL dan ada user yang login
        if ($request->has('read') && Auth::check()) {
            $notification = Auth::user()->notifications()->where('id', $request->query('read'))->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return $next($request);
    }
}