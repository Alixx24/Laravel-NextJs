<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'لطفا ابتدا وارد شوید'
            ], 401);
        }

        $user = Auth::user();
        if ($user->user_type !== 1 || $user->mobile_verified_at === null) {
            return response()->json([
                'message' => 'شما دسترسی لازم برای این عملیات را ندارید'
            ], 403);
        }

        return $next($request);
    }
}
