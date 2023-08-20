<?php

namespace App\Providers;

use App\Http\Traits\Api\V1\HasJwtTokens;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    use HasJwtTokens;
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */

    public function boot(): void
    {
        Auth::viaRequest('jwt', function (Request $request) {
            try {
                $jwt = $request->bearerToken() ?? '';
                return $this->jwtAuthenticatedUser($jwt);
            } catch(\Exception $th) {
                \Log::error($th);
                return null;
            }
        });
    }
}
