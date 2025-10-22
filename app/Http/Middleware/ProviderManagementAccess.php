<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProviderManagementAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has admin role or specific provider management permissions
        if (!$user->isAdmin() && !$user->hasPermission('manage_providers')) {
            abort(403, 'Unauthorized access to provider management features.');
        }

        return $next($request);
    }
}