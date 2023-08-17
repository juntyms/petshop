<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\TestTokenTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminEndpointTest extends TestCase
{
    use HasJwtTokens, TestTokenTrait;
    /** @test */
    public function can_create_admin_account(): void
    {
        $this->withoutExceptionHandling();

        $token = $this->generateToken(); //Create Admin User then generate token

        $response = $this->withHeaders([
                        'Authorization'=>'Bearer '.$token->token,])
                    ->json('POST','/api/v1/admin/create',[
                            'uuid' => '1',
                            'first_name' => fake()->firstname(),
                            'last_name' => fake()->lastName(),
                            'is_admin' => 1,
                            'email' =>fake()->email(),
                            'password' => Hash::make('123456789'),
                            'address' => fake()->address(),
                            'phone_number' => fake()->phoneNumber()
                        ]);

        $response->assertStatus(200);


    }

    /** @test */
    public function can_display_non_admin_users()
    {
        $this->withoutExceptionHandling();

        $token = $this->generateToken(); //Create Admin User then generate token

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token->token])
                        ->get('/api/v1/admin/user-listing');

        $response->assertStatus(200);


    }

    /** @test */
    public function admin_can_login()
    {
        $user = User::where('id',1)->first();

        $userFound = $user ? true : false;

        $this->assertTrue($userFound);

        $response = $this->post('api/v1/admin/login',
            [
                'email' => 'admin@buckhill.co.uk',
                'password' => 'admin'
            ]);

        $response->assertStatus(200);

    }

    /** @test */
    public function admin_can_logout()
    {
         //Create Admin User then generate token
        $token = $this->generateToken();

        $response = $this->get('/api/v1/admin/logout',['Authorization'=>'Bearer '.$token->token]);

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_user_can_edit_user_info()
    {

        $token = $this->generateToken(); //Create Admin User then generate token

        $normal_user = User::factory()->create(['is_admin'=>'0']);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token->token])
                        ->json('PUT','/api/v1/admin/user-edit/'.$normal_user->uuid,[
                            'first_name' => 'Michael'
                        ]);
        // Success
        $response->assertStatus(200);

    }

    /** @test */
    public function admin_user_cannot_edit_admin_info()
    {
        //Create Admin User then generate token
        $token = $this->generateToken();

        //Create New Admin User
        $user = User::factory()->create(['is_admin'=>'1']);

        // Edit the Admin User
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token->token])
                        ->json('PUT','/api/v1/admin/user-edit/'.$user->uuid,[
                            'first_name' => 'Jone',
                            'last_name' =>  'Doe',
                            'email' => 'john.doe@email.com',
                            'password' =>  Hash::make('new-password'),
                            'address' => 'New Address',
                            'phone_number' => '123456789'
                        ]);

        // Not Allowed
        $response->assertStatus(403);

    }

    /** @test */
    public function admin_can_delete_normal_user()
    {

        //Create Admin User then generate token
        $token = $this->generateToken();

        $user = User::factory()->create(['is_admin'=>0]);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token->token])
                            ->json('delete','api/v1/admin/user-delete/'.$user->uuid);

        $response->assertStatus(200);

    }

    /** @test */
    public function admin_cannot_delete_admin_user()
    {
        //Create Admin User then generate token
        $token = $this->generateToken();

        $user = User::factory()->create(['is_admin'=>1]);

        $response = $this->withHeaders(['Authorization'=>'Bearer '.$token->token])
                            ->json('delete','api/v1/admin/user-delete/'.$user->uuid);

        $response->assertStatus(403);
    }
}