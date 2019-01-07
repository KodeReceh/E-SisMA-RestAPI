<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LetterTemplate;

class CheckIfUserCanSignThisOutcomingLetter
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
        if (LetterTemplate::canSign($request->route()[2]['id']))
            return $next($request);

        return response()->json([
            'success' => true,
            'description' => 'Tidak memiliki akses.'
        ], 403);
    }
}
