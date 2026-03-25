<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        if (!($user instanceof User)) {
            abort(403);
        }
        if (!is_string($permission) ||!$user->permissions()->contains('name', $permission)) {
            abort(403);
        }

        return $next($request);
    }
}
