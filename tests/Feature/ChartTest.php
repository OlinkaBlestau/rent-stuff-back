<?php

namespace Tests\Feature;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ChartTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        Artisan::call('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user, [UserRoles::LANDLORD->value]);

        $token = $user->token();

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->get('/api/count', $headers);
        //print_r($response->content());

        $response->assertStatus(200);
    }
}
