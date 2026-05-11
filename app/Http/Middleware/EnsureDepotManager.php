<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepotManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'depot_manager') {
            return response()->json([
                'message' => 'Only depot managers can approve transactions.'
            ], 403);
        }

        return $next($request);
    }
}
