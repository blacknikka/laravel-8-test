<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\CookieAuthenticationController;

class CookieAuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function login正常系() {
        User::factory()->create([
            'email' => 'aaa@example.com',
        ]);

        $response = $this->postJson('/login', [
            'email' => 'aaa@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => CookieAuthenticationController::ERR_LOGIN_SUCCEEDED]);
    }

    /** @test */
    public function login異常系() {
        User::factory()->create([
            'email' => 'aaa@example.com',
        ]);

        // try to login with invalid information.
        $response = $this->postJson('/login', [
            'email' => 'bbb@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson(['message' => CookieAuthenticationController::ERR_LOGIN_FILED]);
    }

    /** @test */
    public function logout正常系() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response
            ->assertStatus(200)
            ->assertJson(['message' => CookieAuthenticationController::ERR_LOGOUT_SUCCEEDED]);
    }
}
