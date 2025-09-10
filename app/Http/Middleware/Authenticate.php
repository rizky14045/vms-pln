<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

     /**
     * Custom response when not authenticated
     */
    protected function unauthenticated($request, array $guards)
    {
        // Jika guard-nya "api", kembalikan JSON
        if (in_array('api', $guards)) {
            abort(
                ResponseHelper::response(
                    'Unauthenticated!', // messages
                    null, // errors
                    null, // data
                    401 // status_code
                )
            );
        }

        // Default Laravel behavior
        parent::unauthenticated($request, $guards);
    }
}
