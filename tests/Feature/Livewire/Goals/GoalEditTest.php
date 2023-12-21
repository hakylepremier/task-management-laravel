<?php

namespace Tests\Feature\Livewire\Goals;

use App\Models\Category;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Goal $goal;

    private function createUser(): User
    {
        return User::factory()->create();
    }

    private function createGoal(): Goal
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $goal = Goal::factory()->make(['category_id' => $category->id])->toArray();
        return $this->user->goals()->create($goal);
    }

    // this is for setting up global setups in the testing class
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->goal = $this->createGoal();
    }

    public function test_goal_edit_component_can_render(): void
    {
        $this->actingAs($this->user);
        $component = Volt::test('goals.edit', [$this->goal]);

        $component->assertSee(__('Edit Goal'));
    }

    public function test_goal_can_be_edited()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $goalUpdate = [
            'title' => 'An updated title',
            'description' => 'An updated description.',
            'category_id' => $category->id,
            'end_date' => today(),
        ];

        $this->assertDatabaseCount('goals', 1);
        $this->assertDatabaseCount('categories', 2);

        $lastGoal = Goal::where("id", "=", $this->goal->id)->first();

        $component = Volt::test('goals.edit', [$this->goal])
            ->set('goal', $this->goal)
            ->set('title', $goalUpdate['title'])
            ->set('description', $goalUpdate['description'])
            ->set('category_id', $goalUpdate['category_id'])
            ->set('end_date', $goalUpdate['end_date'])
            ->call('update');

        $component->assertDispatched('goal-updated');

        $this->assertNotEquals($goalUpdate['title'], $lastGoal->title);
        $this->assertNotEquals($goalUpdate['description'], $lastGoal->description);
        $this->assertDatabaseMissing('goals', $lastGoal->toArray());
        $this->assertDatabaseHas('goals', $goalUpdate);
        $this->assertDatabaseCount('goals', 1);
    }

    public function test_goals_update_throws_errors_for_invalid_data(): void
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $goalUpdate = [
            'title' => null,
            'description' => 1234.37,
            'category_id' => "test",
            'end_date' => '2000-01-01',
        ];

        $this->assertDatabaseCount('goals', 1);

        $lastGoal = Goal::where("id", "=", $this->goal->id)->first();

        $component = Volt::test('goals.edit', [$this->goal])
            ->set('goal', $this->goal)
            ->set('title', $goalUpdate['title'])
            ->set('description', $goalUpdate['description'])
            ->set('category_id', $goalUpdate['category_id'])
            ->set('end_date', $goalUpdate['end_date'])
            ->call('update');


        $component
            ->assertHasErrors(['title' => 'required', 'description' => 'string', 'category_id' => 'integer', 'end_date' => 'after_or_equal:today']);

        $this->assertDatabaseHas('goals', $lastGoal->toArray());
        $this->assertDatabaseMissing('goals', $goalUpdate);
    }
}
