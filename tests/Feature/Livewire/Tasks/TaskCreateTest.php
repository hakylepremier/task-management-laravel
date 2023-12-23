<?php

namespace Tests\Feature\Livewire\Tasks;

use App\Models\Category;
use App\Models\Goal;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class TaskCreateTest extends TestCase
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

    public function test_task_create_component_can_render(): void
    {
        $this->actingAs($this->user);
        $state = 'To do';
        $component = Volt::test('tasks.create', ['state' => $state]);

        $component->assertSee('Create Task');
    }

    public function test_task_create_can_create_a_new_task_for_a_specific_goal(): void
    {
        $this->actingAs($this->user);
        $title = 'A fake title';
        $description = fake()->realText(200);
        $state = 'To do';
        // $test = $goal->toArray();
        $task = [
            'title' => $title,
            'description' => $description,
        ];

        $component = Volt::test('tasks.create', ['state' => $state, 'goal' => $this->goal])
            ->set('title', $task['title'])
            ->set('description', $task['description']);

        $component->call('store');

        $lastTask = Task::latest()->first();
        $taskState = $lastTask->state;

        $component
            ->assertHasNoErrors();

        // user id must be null and goal id must be the same as this goal
        $this->assertDatabaseHas('tasks', $task);
        $this->assertEquals($task['title'], $lastTask->title);
        $this->assertEquals($task['description'], $lastTask->description);
        $this->assertEquals($this->goal->id, $lastTask->goal_id);
        $this->assertEquals($state, $taskState->title);
        $this->assertNull($lastTask->user_id);
    }

    public function test_task_create_can_create_a_new_task_without_a_specific_goal(): void
    {
        $this->actingAs($this->user);
        $title = 'A fake title';
        $description = fake()->realText(200);
        $state = 'To do';

        $task = [
            'title' => $title,
            'description' => $description,
        ];

        $component = Volt::test('tasks.create', ['state' => $state])
            ->set('title', $task['title'])
            ->set('description', $task['description']);

        $component->call('store');

        $lastTask = Task::latest()->first();
        $taskState = $lastTask->state;

        $component
            ->assertHasNoErrors();

        // goal_id must be null, user_id must equal this user's id
        $this->assertDatabaseHas('tasks', $task);
        $this->assertEquals($task['title'], $lastTask->title);
        $this->assertEquals($task['description'], $lastTask->description);
        $this->assertNull($lastTask->goal_id);
        $this->assertEquals($state, $taskState->title);
        $this->assertEquals($this->user->id, $lastTask->user_id);
    }

    public function test_task_create_can_create_a_new_task_with_only_required_fields(): void
    {
        $this->actingAs($this->user);
        $title = 'A fake title';
        $state = 'To do';
        // $test = $goal->toArray();
        $task = [
            'title' => $title,
            'description' => null,
        ];

        $component = Volt::test('tasks.create', ['state' => $state, 'goal' => $this->goal])
            ->set('title', $task['title']);

        $component->call('store');

        $lastTask = Task::latest()->first();
        $taskState = $lastTask->state;

        $component
            ->assertHasNoErrors();

        // user id must be null and goal id must be the same as this goal
        $this->assertDatabaseHas('tasks', $task);
        $this->assertEquals($task['title'], $lastTask->title);
        $this->assertNull($lastTask->description);
        $this->assertEquals($this->goal->id, $lastTask->goal_id);
        $this->assertEquals($state, $taskState->title);
        $this->assertNull($lastTask->user_id);
    }

    // TODO: Add sad path tests for task create functionality
}
