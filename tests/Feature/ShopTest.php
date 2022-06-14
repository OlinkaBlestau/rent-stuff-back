<?php

namespace Tests\Feature;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ShopTest extends TestCase
{

    public function test_create_shop_successful()
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->post('/api/shop', [
            'name' => 'fdfd',
            'phone' => '+380665932159',
            'address'=> 'fbdbdf',
            'longitude' => '65151',
            'latitude' => '56116',
            'description' => 'dfbdfb',
            'email' => 'dfvd@kh.ua',
            'user_id' => '30'
        ]);
        //print_r($response->content());
        $response
            ->assertStatus(201);
    }

    public function test_create_shop_faild()
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->postJson('/api/shop', [
            'name' => '',
            'phone' => '',
            'address'=> '',
            'longitude' => '',
            'latitude' => '',
            'description' => '',
            'email' => '',
            'user_id' => '30'
        ]);
        //print_r( "\n" . "Field is empty");
        $response
            ->assertStatus(422);
    }

    public function test_view_shop_successful()
    {
        $response = $this->get('/api/shop/5');
        //print_r( "\n" . $response->content());
        $response->assertStatus(200);
    }

    public function test_view_shop_failed()
    {
        $response = $this->get('/api/user/10000');
        //print_r( "\n" . "User not found");
        $response->assertStatus(404);
    }

    public function test_update_shop_successful(): void
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];
        $payload = [
            'name' => 'fdfsdd',
            'phone' => '+380665932159',
            'address'=> 'fbdbdf',
            'longitude' => '65151',
            'latitude' => '56116',
            'description' => 'dfbdfb',
            'email' => 'dfvd@kh.ua',
            'user_id' => '10'
        ];
        $response = $this->json('PUT', '/api/shop/8', $payload, $headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);
        //print_r( "\n" . $response->content());
    }

    public function test_update_shop_failed(): void
    {
        $payload = [
            'name' => 'fdfsdd',
            'phone' => '+380665932159',
            'address'=> 'fbdbdf',
            'longitude' => '65151',
            'latitude' => '56116',
            'description' => 'dfbdfb',
            'email' => 'dfvd@kh.ua',
            'user_id' => '10'
        ];
        $response = $this->json('PUT', '/api/shop/8', $payload);
        $response
            ->assertStatus(401);

        //print_r( "\n" . $response->content());
    }
}
