<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has required role
        $user = Auth::user();
        if (!in_array($user->role, $roles)) {
            // If user is admin trying to access customer routes, redirect to admin dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // If user is customer trying to access admin routes, show 403
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
