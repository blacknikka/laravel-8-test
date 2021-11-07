<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create(['email' => 'user1@example.com']);
        \App\Models\User::factory()->create(['email' => 'user2@example.com']);
        \App\Models\User::factory()->create(['email' => 'user3@example.com']);
        \App\Models\Memo::factory(3)->create();
    }
}
