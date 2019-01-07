<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\IncomingLetter;

class CheckIfUserHasAccessToThisIncomingLetter
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
        $incomingLetter = IncomingLetter::find($request->route()[2]['id']);
        if (!$incomingLetter)
            return response()->json([
            'success' => false,
            'description' => 'Data tidak ditemukan.'
        ], 404);

        $recipients = $incomingLetter->dispositions()->pluck('id')->toArray();

        if ($user->role->has('super_user') || $user->role->has('atur_surat_masuk'))
            return $next($request);

        if (in_array($user->id, $recipients)) return $next($request);

        return response()->json([
            'success' => true,
            'description' => 'Tidak bisa melihat data ini.'
        ], 403);
    }
}
