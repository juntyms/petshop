<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\TestTokenTrait;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertEquals;

class UserEndpointTest extends TestCase
{
    use HasJwtTokens;
    use TestTokenTrait;
    use RefreshDatabase;


    /** @test */
    public function normal_user_can_login()
    {
        $user = User::factory()->create(['is_admin' => '0','password' => 'userpassword']);

        $response = $this->post(
            '/api/v1/admin/login',
            [
                'email' => $user->email,
                'password' => 'userpassword'
            ]
        );

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_logout()
    {

        $token = $this->generateToken(0);

        $response = $this->get('/api/v1/user/logout', ['Authorization' => 'Bearer '.$token->token]);

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_create_user_account()
    {
        $response = $this->post('/api/v1/user/create', [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'email' => fake()->safeEmail(),
                        'is_admin' => 0,
                        'password' => fake()->randomAscii(),
                        'avatar' => fake()->uuid(),
                        'address' => fake()->address(),
                        'phone_number' => fake()->phoneNumber(),
                        'is_marketing' => fake()->numberBetween(0, 1)
                    ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function user_can_not_create_admin_account()
    {
        $response = $this->post('/api/v1/user/create', [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'email' => fake()->safeEmail(),
                        'is_admin' => 1,
                        'password' => fake()->randomAscii(),
                        'avatar' => fake()->uuid(),
                        'address' => fake()->address(),
                        'phone_number' => fake()->phoneNumber(),
                        'is_marketing' => fake()->numberBetween(0, 1)
                    ]);

        $response->assertStatus(201);

        assertEquals($response['data']['attributes']['is_admin'], false);

    }

    /** @test */
    public function user_can_create_token_to_reset_password()
    {
        $created_account = $this->post('/api/v1/user/create', [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'email' => fake()->safeEmail(),
                        'is_admin' => 0,
                        'password' => fake()->randomAscii(),
                        'avatar' => fake()->uuid(),
                        'address' => fake()->address(),
                        'phone_number' => fake()->phoneNumber(),
                        'is_marketing' => fake()->numberBetween(0, 1)
                    ]);

        $response = $this->post('/api/v1/user/forgot-password', [
            'email' => $created_account['data']['attributes']['email']
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function not_existing_user_cannot_create_token_reset_password()
    {

        $response = $this->post('/api/v1/user/forgot-password', [
                    'email' => 'dontexistemail@email.com'
                ]);

        $response->assertStatus(404);

    }
    /** @test */
    public function user_can_reset_password_with_a_token()
    {

        $created_account = $this->post('/api/v1/user/create', [
                                'first_name' => fake()->firstName(),
                                'last_name' => fake()->lastName(),
                                'email' => fake()->safeEmail(),
                                'is_admin' => 0,
                                'password' => fake()->randomAscii(),
                                'avatar' => fake()->uuid(),
                                'address' => fake()->address(),
                                'phone_number' => fake()->phoneNumber(),
                                'is_marketing' => fake()->numberBetween(0, 1)
                            ]);

        $token_created = $this->post('/api/v1/user/forgot-password', [
            'email' => $created_account['data']['attributes']['email']
        ]);



        $response = $this->post('/api/v1/user/reset-password-token', [
                        'email' => $created_account['data']['attributes']['email'],
                        'password' => '1234567890',
                        'password_confirmation' => '1234567890',
                        'token' => $token_created['data']['token']
                    ]);

        $response->assertStatus(200);

    }

    /** @test */
    public function user_cannot_reset_password_with_a_token_and_wrong_password_confirmation()
    {

        $created_account = $this->post('/api/v1/user/create', [
                                        'first_name' => fake()->firstName(),
                                        'last_name' => fake()->lastName(),
                                        'email' => fake()->safeEmail(),
                                        'is_admin' => 0,
                                        'password' => fake()->randomAscii(),
                                        'avatar' => fake()->uuid(),
                                        'address' => fake()->address(),
                                        'phone_number' => fake()->phoneNumber(),
                                        'is_marketing' => fake()->numberBetween(0, 1)
                                    ]);

        $created_account->assertCreated();

        $token_created = $this->post('/api/v1/user/forgot-password', [
            'email' => $created_account['data']['attributes']['email']
        ]);

        $token_created->assertOk();

        $response = $this->post('api/v1/user/reset-password-token', [
                        'email' => $created_account['data']['attributes']['email'],
                        'password' => '1234567890',
                        'password_confirmation' => '123879779797970',
                        'token' => $token_created['data']['token']
                    ]);

        $response->assertStatus(302);

    }
    /** @test */
    public function user_can_view_own_user_account()
    {
        $token = $this->generateToken(0);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token->token])
                        ->get('api/v1/user');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_delete_own_user_account()
    {

        $token = $this->generateToken(0);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token->token])
                        ->delete('api/v1/user');

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_list_all_owned_orders()
    {

        $token = $this->generateToken(0);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token->token])
                        ->get('api/v1/user/orders');

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_edit_own_account()
    {

        $user = User::factory()->create(['is_admin' => 0]);

        $token = $this->generateToken(0);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token->token])
                        ->json('PUT', 'api/v1/user/edit', [
                            'first_name' => 'Michael',
                            'address' => 'New Address'
                        ]);

        $response->assertStatus(200);

    }
}
