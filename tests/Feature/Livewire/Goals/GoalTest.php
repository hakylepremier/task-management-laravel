<?php

namespace Tests\Feature\Livewire\Goals;

use App\Models\Category;
use App\Models\Goal;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private function createUser(): User
    {
        return User::factory()->create();
    }

    // this is for setting up global setups in the testing class
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
    }

    public function test_goal_component_can_render(): void
    {
        Category::factory()->create([
            'user_id' => $this->user->id
        ]);
        $category = $this->user->categories()->first();
        Goal::factory()->create([
            'category_id' => $category->id,
            'stage_id' => Stage::factory()->create(['title' => 'Processing'])->id,
            'user_id' => $this->user->id
        ]);
        $goal = $this->user->goals()->with('category')->first();
        // $goals = $this->user->goals()->with('category')->latest()->get();
        // foreach ($goals as $goal) {
        //     $component = Volt::test('goals.goal', ['goal' => $goal]);
        //     $component->assertSee($goal->title);
        //     $component->assertSee($category->title);
        // }

        $component = Volt::test('goals.goal', ['goal' => $goal]);
        $component->assertSee($goal->title);
        $component->assertSee($category->title);
        // $goalArr = $goal->toArray();


    }
}
