<?php

namespace App\Http\Traits\Api\V1;

use App\Models\User;

trait TestTokenTrait
{
    use HasJwtTokens;


    public function generateToken()
    {
        $user = User::factory()->create(['is_admin'=>1]);

        $this->token = $this->createToken($user->uuid);

        return $this;

    }
}