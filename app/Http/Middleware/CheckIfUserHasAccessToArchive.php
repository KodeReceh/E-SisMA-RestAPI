<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Archive;

class CheckIfUserHasAccessToArchive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = app('auth')->user();
        $archive = Archive::find($request->route()[2]['id']);

        if ($archive) {
            if ($user->role_id == $archive->role_id) return $next($request);
        }

        return response()->json([
            'success' => true,
            'description' => 'Tidak bisa melihat data ini.'
        ], 403);
    }
}
