<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Category::factory(10)->create();
        \App\Models\Goal::factory(2)->create([
            'user_id' => 1
        ]);
        \App\Models\State::factory()->create([
            'title' => 'To do'
        ]);
        \App\Models\State::factory()->create([
            'title' => 'In Progress'
        ]);
        \App\Models\State::factory()->create([
            'title' => 'Done'
        ]);
        // $user = \App\Models\User::where('id', '=', 1)->first();
        // $user->goals()->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
