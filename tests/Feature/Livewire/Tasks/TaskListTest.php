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

class TaskListTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private Goal $goal;
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
  }

  public function test_task_list_can_render(): void
  {
    $this->actingAs($this->user);
    $component = Volt::test('tasks.components.task-list', ['goal' => $this->goal, 'state' => $this->states->first()->id, 'states' => $this->states]);

    $component->assertSee('');
  }

  public function test_task_list_updates_when_task_is_created()
  {
    $this->actingAs($this->user);
    $state = $this->states->first();
    $taskCreated = [
      'title' => 'A fake title',
      'description' => 'A fake description',
      'state_id' => $state->id
    ];

    $component = Volt::test('tasks.components.task-list', ['goal' => $this->goal, 'state' => $this->states->first()->id, 'states' => $this->states]);

    $component->assertDontSee('A fake title');

    $this->goal->tasks()->create($taskCreated);
    // $task = Task::factory()->create(['goal_id' => $this->goal->id]);
    $this->assertDatabaseCount('tasks', 1);

    $component->dispatch('task-created');
    $component->assertSee($taskCreated['title']);
  }

  public function test_task_list_updates_when_task_is_deleted()
  {
    $this->actingAs($this->user);
    $state = $this->states->first();
    $taskCreated = [
      'title' => 'A fake title',
      'description' => 'A fake description',
      'state_id' => $state->id
    ];

    $this->goal->tasks()->create($taskCreated);
    $this->assertDatabaseCount('tasks', 1);
    $lastTask = Task::latest()->first();

    $component = Volt::test('tasks.components.task-list', ['goal' => $this->goal, 'state' => $this->states->first()->id, 'states' => $this->states]);

    $component->assertSee($taskCreated['title']);

    $lastTask->delete();

    $this->assertDatabaseCount('tasks', 0);

    $component->dispatch('task-deleted');
    $component->assertDontSee($taskCreated['title']);
  }
  // TODO: Add sad path tests for task create functionality
}
