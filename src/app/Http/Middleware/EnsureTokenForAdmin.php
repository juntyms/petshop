<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenForAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Check if Auth user token is admin
        if (Auth::user()->is_admin !== 1) {
            //abort('401','Unauthorize Access');
            abort(response()->json('Unauthorized Access', 403));
        }

        return $next($request);
    }
}
