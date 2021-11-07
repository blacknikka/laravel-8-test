<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Memo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        User::factory()->create(['email' => 'user2@example.com']);
        User::factory()->create(['email' => 'user3@example.com']);

        // user
        Memo::factory()
            ->count(3)
            ->for($user1, 'author')
            ->create();
    }
}
