<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek jika user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek role user sesuai parameter
        $user = Auth::user();
        
        if ($user->role === $role) {
            return $next($request);
        }

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
            case 'superadmin':
                return redirect()->route('admin.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                abort(403, 'Unauthorized access.');
        }
    }
}