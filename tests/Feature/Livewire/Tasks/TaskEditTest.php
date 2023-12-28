<?php

namespace Tests\Feature\Livewire\Tasks;

use App\Models\Category;
use App\Models\Goal;
use App\Models\State;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class TaskEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Goal $goal;
    private $states;
    private Task $task;

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
        $state1 = State::factory()->create([
            'title' => 'To do'
        ]);
        $state2 = State::factory()->create([
            'title' => 'In Progress'
        ]);
        $state3 = State::factory()->create([
            'title' => 'Done'
        ]);
        $this->states = State::whereIn('title', [$state1->title, $state2->title, $state3->title])->get();
        $this->user = $this->createUser();
        $this->goal = $this->createGoal();
        $this->task = $this->goal->tasks()->create(['title' => 'A fake task title', 'description' => 'A fake task description', 'state_id' => $state1->id]);
    }

    public function test_task_edit_component_can_render(): void
    {
        $this->actingAs($this->user);
        $state = 'To do';
        $component = Volt::test('tasks.edit', ['task' => $this->task]);

        $component->assertSee('Edit Task');
    }

    /**
     * 1. task can be updated by authenticated user
     * 2. task cannot be updated by unauthenticated user
     * 3. task update throws error for invalid data
     */

    public function test_task_can_be_edited(): void
    {
        $this->actingAs($this->user);
        $title = 'An updated title';
        $description = fake()->realText(200);
        $state = 'To do';
        // $test = $goal->toArray();
        $task = [
            'title' => $title,
            'description' => $description,
        ];

        $lastTask = Task::latest()->first()->toArray();

        $component = Volt::test('tasks.edit', ['task' => $lastTask])
            ->set('title', $task['title'])
            ->set('description', $task['description'])
            ->call('update');

        $component->assertDispatched('task-updated');

        // $taskState = $lastTask->state;

        $component
            ->assertHasNoErrors();

        // user id must be null and goal id must be the same as this goal
        $this->assertDatabaseHas('tasks', $task);
        $this->assertDatabaseMissing('tasks', $lastTask);
        $this->assertNotEquals($task['title'], $lastTask['title']);
        $this->assertNotEquals($task['description'], $lastTask['description']);
    }

    public function test_task_update_throws_errors_for_invalid_data(): void
    {
        $this->actingAs($this->user);
        // $test = $goal->toArray();
        $task = [
            'title' => null,
            'description' => 1234.37,
        ];

        $lastTask = Task::latest()->first()->toArray();

        $component = Volt::test('tasks.edit', ['task' => $lastTask])
            ->set('title', $task['title'])
            ->set('description', $task['description'])
            ->call('update');

        $component
            ->assertHasErrors(['title' => 'required', 'description' => 'string']);

        // user id must be null and goal id must be the same as this goal
        $this->assertDatabaseMissing('tasks', $task);
        $this->assertDatabaseHas('tasks', $lastTask);
    }
    // TODO: Add sad path tests for task update functionality
}
