<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return Auth::check() && Auth::user()->role === \App\Models\User::ROLE_ADMIN;
    }
}

if (! function_exists('is_lawyer')) {
    function is_lawyer(): bool
    {
        return Auth::check() && Auth::user()->role === \App\Models\User::ROLE_LAWYER;
    }
}

if (! function_exists('is_client')) {
    function is_client(): bool
    {
        return Auth::check() && Auth::user()->role === \App\Models\User::ROLE_CLIENT;
    }
}

if (! function_exists('is_supervisor')) {
    function is_supervisor(): bool
    {
        return Auth::check() && Auth::user()->role === \App\Models\User::ROLE_SUPERVISOR;
    }
}
if (! function_exists('admin_asset')) {
    /**
     * Return the asset path for admin dashboard.
     *
     * @param  string  $path
     * @return string
     */
    function admin_asset($path)
    {
        return asset('assets/Backend/'.ltrim($path, '/'));
    }
}

if (! function_exists('getAdminUserFromToken')) {
    function getAdminUserFromToken()
    {
        $token = session('admin_jwt_token');

        if (! $token) {
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        $payload = $payload['user'] ?? null;
        $payload = $payload['id'] ?? null;
        $user = User::Where('id', $payload)->first();

        // dd( $payload,$user );
        return $user ?? null;
    }
}

if (! function_exists('generateUserStats')) {
    function generateUserStats($role)
    {
        return [
            'total' => User::where('role', $role)->count(),

            'completed_profiles' => User::where('role', $role)
                ->whereNotNull('email')
                ->whereNotNull('gender')
                ->WhereNotNull('first_name')
                // ->whereNotNull('mobile_number')
                // ->whereNotNull('mobile_country_code')
                ->count(),

            'incomplete_profiles' => User::where('role', $role)
                ->where(function ($q) {
                    $q->whereNull('email')
                        ->orWhereNull('gender')
                        ->orWhereNull('country')
                        ->orWhereNull('first_name');
                })->count(),

            'active' => User::where('role', $role)->where('is_active', 1)->count(),

            'pending' => User::where('role', $role)->where('is_active', 0)->count(),

            'banned' => User::where('role', $role)->where('is_active', 2)->count(),
        ];
    }

}

if (! function_exists('getUsersByRoles')) {
    function getUsersByRoles()
    {
        $providers = User::where('role', 1)->get();
        $requesters = User::where('role', 2)->get();

        return [
            'providers' => $providers,
            'requesters' => $requesters,
            'difference' => abs($providers->count() - $requesters->count()),
        ];
    }
}
