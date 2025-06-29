<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminDashboardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminPrefix = trim(env('ADMIN_DASHBOARD', 'admin'), '/');
        $token = session('admin_jwt_token');
        $loginRoute = "/$adminPrefix/login";
        $dashboardRoute = "/$adminPrefix";

        $user = $token ? JWTAuth::setToken($token)->authenticate() : null;

        $userId = $user ? $user->id : null;

        $user = $userId ? User::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            session(['url.intended' => $request->fullUrl()]);

            return redirect($loginRoute);
        }

        //  $locale = session('admin_locale', config('app.locale'));
        $locale = session('admin_locale', 'ar');
        App::setLocale($locale);

        return $next($request);
    }
}
