<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            "To do",
            "In Progress",
            "Done"
        ];

        foreach ($states as $state) {
            $mystate = \App\Models\State::firstOrCreate([
                'title' => $state,
            ]);
        }
    }
}
