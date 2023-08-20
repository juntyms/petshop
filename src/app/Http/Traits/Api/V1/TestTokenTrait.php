<?php

namespace App\Http\Traits\Api\V1;

use App\Models\User;

trait TestTokenTrait
{
    use HasJwtTokens;

    public function generateToken($userType)
    {
        $user = User::factory()->create(['is_admin' => $userType]);

        $this->token = $this->createToken($user->uuid);

        return $this;
    }
}
