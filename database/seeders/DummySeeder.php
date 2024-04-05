<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $dummyuser = \App\Models\User::firstOrCreate([
        //     'name' => 'Dummy User',
        //     'email' => 'dummy@dummy.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'remember_token' => Str::random(10),
        // ]);

        if (User::where('email', 'dummy@dummy.com')->count() == 0) {
            \App\Models\User::factory()->create([
                'name' => 'Dummy User',
                'email' => 'dummy@dummy.com',
            ]);
        }

        if (State::count() < 3) {
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

        $dummyuser = User::where('email', 'dummy@dummy.com')->first();


        $goals = [
            "Build Website" => [
                "Build HTML Structure",
                "Add CSS styles",
                "Add JS interactivity"
            ],
            "Build Cooking Robot" => [
                "Make CAD Model",
                "3D Print Model",
                "Buy Mechanical Parts",
                "Assemble Robot",
                "Program Raspberry Pi",
            ],
            "Clean the House" => [
                "Clean Bedroom",
                "Mop floors",
                "Clean Kitchen"
            ],
        ];

        foreach ($goals as $goal => $tasks) {
            $myGoal = \App\Models\Goal::firstOrCreate([
                'title' => $goal,
                'user_id' => $dummyuser->id,
                'stage_id' => Stage::firstOrCreate(['title' => 'Processing'])->id,
            ]);


            foreach ($tasks as $task) {
                $state_id = fake()->randomElement(State::all())['id'];
                $myTask = \App\Models\Task::firstOrCreate([
                    'title' => $task,
                    'goal_id' => $myGoal->id,
                    'state_id' => $state_id
                ]);
            }
        }
    }
}
