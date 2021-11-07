<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function me正常系() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.me'));
        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
