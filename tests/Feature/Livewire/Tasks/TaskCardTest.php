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

class TaskCardTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private Goal $goal;
  private Task $task;
  private $states;

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

  public function test_task_card_can_render(): void
  {
    $task = Task::factory()->create(['user_id' => $this->user->id]);
    $this->actingAs($this->user);
    $component = Volt::test('tasks.components.task-card', [$task, $this->states]);

    $component->assertSee('');
  }

  public function test_task_can_be_deleted_on_goal_component()
  {
    $this->actingAs($this->user);
    $state = $this->states->first();

    $this->assertDatabaseCount('tasks', 1);

    $this->assertDatabaseHas('tasks', $this->task->toArray());

    $component = Volt::test('tasks.components.task-card', ['task' => $this->task, 'states' => $this->states])
      ->call('delete', $this->task->id);

    $mytask = $this->task->only(['title', 'description']);
    $this->assertDatabaseCount('tasks', 0);
    $this->assertDatabaseMissing('tasks', $mytask);
  }

  public function test_task_state_can_be_changed(): void
  {
    $this->actingAs($this->user);
    $state1 = State::where('title', '=', 'To do')->first();
    $state2 = State::where('title', '=', 'In Progress')->first();

    $this->assertEquals($this->task->state_id, $state1->id);

    $component = Volt::test('tasks.components.task-card', [$this->task, $this->states])
      ->set('state_id', $state2->id)
      ->call('updateState');

    $component->assertDispatched('task-state-updated');

    $this->task->refresh();
    $this->assertEquals($this->task->state_id, $state2->id);
  }
  // TODO: Add sad path tests for task create functionality
}
