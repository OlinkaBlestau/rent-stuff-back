<?php

namespace Tests\Feature;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ThingTest extends TestCase
{
    public function test_create_thing_successful()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->postJson('/api/thing', [
            'name' => 'fdfd',
            'price' => 100,
            'description'=> 'fbdbdf',
            'photo'=> '9.jpeg\\data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTExMWFhUXGBgaFxgYGB0YGxgYGhgXGhoY',
            'shop_id' => '5',
            'category_id' => '1',
        ], $headers);
        //print_r($response->content());
        $response
            ->assertStatus(200);
    }

    public function test_create_thing_failed()
    {
        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->postJson('/api/thing', [
            'name' => '',
            'phone' => '',
            'address'=> '',
            'longitude' => '',
            'latitude' => '',
            'description' => '',
            'email' => '',
            'user_id' => '30'
        ], $headers);
        //print_r( "\n" . "Field is empty");
        $response
            ->assertStatus(422);
    }

    public function test_update_thing_successful(): void
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];
        $payload = [
            'name' => 'fdfd',
            'price' => 100,
            'description'=> 'fbdbdf',
            'photo'=> '9.jpeg\\data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTExMWFhUXGBgaFxgYGB0YGxgYGhgXGhoY',
            'shop_id' => '5',
            'category_id' => '1',
        ];
        $response = $this->json('PUT', '/api/thing/29', $payload, $headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'update' => true,
            ]);
        //print_r( "\n" . $response->content());
    }

    public function test_update_thing_failed(): void
    {
        $payload = [
            'name' => '',
            'price' => null,
            'description'=> '',
            'photo'=> '',
            'shop_id' => '',
            'category_id' => '',
        ];
        $response = $this->json('PUT', '/api/thing/29', $payload);
        $response
            ->assertStatus(422);

        //print_r( "\n" . $response->content());
    }

    public function test_show_all_thing_successful()
    {
        $response = $this->getJson('/api/thing');
        //print_r($response->content());
        $response
            ->assertStatus(200);
    }

    public function test_show_by_user_thing_successful()
    {
        $response = $this->getJson('/api/thing/byUser/10');
        //print_r($response->content());
        $response
            ->assertStatus(200);
    }

    public function test_show_by_user_thing_failed()
    {
        $response = $this->getJson('/api/thing/byUser/10000');
        //print_r($response->content());
        $response
            ->assertStatus(404);
    }

    public function test_show_thing_successful()
    {
        $response = $this->getJson('/api/thing/29');
        //print_r($response->content());
        $response
            ->assertStatus(200);
    }

    public function test_show_thing_failed()
    {
        $response = $this->getJson('/api/thing/10000');
        //print_r($response->content());
        $response
            ->assertStatus(404);
    }
}
