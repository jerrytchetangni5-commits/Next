<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //verifie si l'utilisateur est connecté et admin
        if(!$request->user() || $request->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Accès refusé💀'
            ], 403);
        }
        return $next($request);
    }
}
