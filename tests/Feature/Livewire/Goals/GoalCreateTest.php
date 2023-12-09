<?php

namespace Tests\Feature\Livewire\Goals;

use App\Models\Goal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalCreateTest extends TestCase
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

    public function test_it_can_render(): void
    {
        $this->actingAs($this->user);
        $component = Volt::test('goals.create');

        $component->assertSee(__('Create Goal'));
    }

    public function test_goal_create_can_create_a_new_goal(): void
    {
        $title = 'A fake title';
        $description = fake()->realText(200);
        $goal = [
            'title' => $title,
            'description' => $description,
            'category_id' => $this->user->categories()->create(['title' => 'Cat Title'])->id,
            'end_date' => Carbon::now()->addWeek(2),
        ];

        $this->actingAs($this->user);
        $component = Volt::test('goals.create')
            ->set('title', $goal['title'])
            ->set('description', $goal['description'])
            ->set('category_id', $goal['category_id'])
            ->set('end_date', $goal['end_date']);
        $component->call('store');

        // $component->actingAs($this->user)->call('store');

        $lastGoal = Goal::latest()->first();
        // $lastGoalArr = $lastGoal->toArray();

        $component
            ->assertHasNoErrors();

        $this->assertDatabaseHas('goals', $goal);
        $this->assertEquals($goal['title'], $lastGoal->title);
        $this->assertEquals($goal['description'], $lastGoal->description);
        $this->assertEquals($goal['category_id'], $lastGoal->category_id);
        $this->assertEquals($goal['end_date'], $lastGoal->end_date);
    }

    public function test_goal_create_can_create_a_new_goal_with_only_required_fields(): void
    {
        $title = 'A fake title';
        $goal = [
            'title' => $title,
        ];

        $this->actingAs($this->user);
        $component = Volt::test('goals.create')
            ->set('title', $goal['title']);
        $component->call('store');

        // $component->actingAs($this->user)->call('store');

        $lastGoal = Goal::latest()->first();
        // $lastGoalArr = $lastGoal->toArray();

        $component
            ->assertHasNoErrors();

        $this->assertDatabaseHas('goals', $goal);
        $this->assertEquals($goal['title'], $lastGoal->title);
        $this->assertNull($lastGoal->description);
        $this->assertNull($lastGoal->category_id);
        $this->assertNull($lastGoal->end_date);
    }

    // TODO: Add sad path tests for goal create functionality
}
