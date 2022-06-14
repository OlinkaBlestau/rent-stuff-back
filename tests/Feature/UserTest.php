<?php

namespace Tests\Feature;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_login_successful()
    {
        $response = $this->post('/api/login', [
            'email' => 'oksana@kh.ua',
            'password' =>  '111111'
        ]);
        //print_r($response->content());
        $response
            ->assertJson([
                'userId' => 10,
                'role' => 'landlord',
                'token_type' => 'Bearer',

            ])
            ->assertStatus(200);
    }

    public function test_login_failed()
    {
        $response = $this->post('/api/login', [
            'email' => 'oksana@kh.ua',
            'password' =>  'asdasdasd'
        ]);
        //print_r($response->content());
        $response
            ->assertJson([
                'message' => 'You cannot sign with those credentials',
                'errors' => 'Unauthorised',
            ])
            ->assertStatus(401);
    }

    public function test_registration_successful()
    {
        $response = $this->post('/api/register', [
            'name' => $this->faker->firstName(),
            'surname' =>  $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' =>  $this->faker->phoneNumber(),
            'password' => '111111',
            'role' => 'landlord'
        ]);
        //print_r($response->content());
        $response
            ->assertJson([
                'message' => 'You were successfully registered. Use your email and password to sign in.',
            ])
            ->assertStatus(201);
    }

    public function test_registration_failed()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'surname' =>  '',
            'email' => '',
            'phone' =>  '',
            'password' => '',
            'role' => ''
        ]);
        //print_r("\n" . $response->content());
        $response->assertStatus(422);
    }

    public function test_update_successful(): void
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];
        $payload = [
            'name' => 'efwijefw',
            'surname' => 'wefwjeif',
            'phone' => '+380958305593',
            'email' => 'ardadwe@gmail.com',
            'password' => 'e32323w',
            'role' => $user->role
        ];
        $response = $this->json('PUT', '/api/user/' . $user->id, $payload, $headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);
        //print_r( "\n" . $response->content());

    }

    public function test_update_failed(): void
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];
        $payload = [
            'name' => '',
            'surname' => '',
            'phone' => '',
            'email' => '',
            'password' => 'e32323w',
            'role' => $user->role
        ];
        $response = $this->json('PUT', '/api/user/' . $user->id, $payload, $headers);
        $response
            ->assertStatus(422);
        //print_r( "\n" . $response->content());

    }

    public function test_viewUser_successful()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();
        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->get('/api/user/'. $user->id, $headers);
        //print_r( "\n" . $response->content());
        $response->assertStatus(200);
    }

    public function test_viewUser_failed()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();
        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->get('/api/user/100'. $user->id, $headers);
        //print_r( "\n" . "User not found");
        $response->assertStatus(404);
    }

    public function test_logout_successful(): void
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];
        $response = $this->postJson('/api/logout', [], $headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'You are successfully logged out',
            ]);
        //print_r( "\n" . $response->content());
    }

    public function test_logout_failed(): void
    {
        $response = $this->postJson('/api/logout');
        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
        //print_r( "\n" . $response->content());

    }
}
