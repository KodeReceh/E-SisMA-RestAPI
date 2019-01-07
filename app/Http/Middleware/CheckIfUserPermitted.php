<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfUserPermitted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = app('auth')->user();

        if ($user->role->has($permission) || $user->role->has('super_user')) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'description' => 'Ini area terlarang!'
        ], 403);
    }
}
