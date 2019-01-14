<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Document;

class CheckIfUserHasAccessToDocument
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
        $theDocument = null;

        if (isset($request->route()[2]['id']))
            $theDocument = Document::find($request->route()[2]['id']);
        else if (isset($request->route()[2]['path']))
            $theDocument = Document::where('path', $request->route()[2]['path'])->first();

        if ($theDocument) {
            if ($theDocument->uploader_id == $user->id) return $next($request);

            if ($theDocument->archive) {
                if ($theDocument->archive->role_id == $user->role_id) return $next($request);
            }

            if ($theDocument->incoming_letter) {
                $users = $theDocument->incoming_letter->dispositions()->pluck('user_id')->toArray();
                if (in_array($user->id, $users)) return $next($request);
            }
        }

        return response()->json([
            'success' => true,
            'description' => 'Tidak bisa melihat data ini.'
        ], 403);
    }
}
