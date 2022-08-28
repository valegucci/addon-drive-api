<?php

namespace Vale\Addons\Drive\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DriveApiAuth
{
    /**
     * Checks if API key is set
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->admin_key) || $request->admin_key !== config('addons.addon-drive.api-key')) {
            return response()->json([
                "status" => false,
                "code" => Response::HTTP_FORBIDDEN,
                "exit_code" => 1,
                "messages" => ['Access denied'],
            ], Response::HTTP_FORBIDDEN);
        }

        // Move on, everything's fine
        return $next($request);
    }
}
