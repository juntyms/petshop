<?php

namespace Tests\Unit;

use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EJwtTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function jwt_token_trait_can_generated_with_user_uuid()
    {
        $trait = new class () {
            use HasJwtTokens {
                createToken as public;
            }
        };

        $user = User::factory()->create();

        $this->assertTrue(strlen($trait->createToken($user->uuid)) > 0);
    }

    /** @test */
    public function check_jwt_token_is_valid()
    {
        $trait = new class () {
            use HasJwtTokens {
                jwtAuthenticatedUser as public;
                createToken as public;
            }
        };

        $user = User::factory()->create();

        $token = $trait->createToken($user->uuid);

        $this->assertEquals($trait->jwtAuthenticatedUser($token)->uuid, $user->uuid);
    }
}
