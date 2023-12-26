<?php

namespace Tests\Feature\Livewire\Goals;

use App\Models\Category;
use App\Models\Goal;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalShowTest extends TestCase
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

    public function test_goal_show_can_render(): void
    {
        $this->actingAs($this->user);
        $component = Volt::test('goals.show', [$this->goal]);

        $component->assertSee('');
    }

    public function test_goal_show_route_can_be_visited_by_auth_user(): void
    {
        $response = $this->actingAs($this->user)->get("goals/" . $this->goal->id);

        $response->assertStatus(200)->assertSeeVolt('goals.show');
    }

    public function test_goal_details_can_be_seen_on_goal_show_route(): void
    {
        $response = $this->actingAs($this->user)->get("goals/" . $this->goal->id);

        $response->assertSee($this->goal->title);
        $response->assertSee($this->goal->description);
        $response->assertSee(Carbon::parse($this->goal->end_date)->isoFormat('MMMM Do YYYY'));
        $response->assertSee($this->goal->category->title);
        $response->assertStatus(200)->assertSeeVolt('goals.show');
    }

    public function test_goal_with_only_title_has_alternative_text_shown_on_its_goal_route(): void
    {
        $goal = $this->user->goals()->create([
            'title' => 'A fake title',
            'stage_id' => Stage::firstOrCreate(['title' => 'Processing'])->id
        ]);
        $response = $this->actingAs($this->user)->get("goals/" . $goal->id);

        $response->assertSee('A fake title');
        $response->assertSee('No category added');
        $response->assertSee('No description provided');
        $response->assertSee('No completion date given.');
        $response->assertStatus(200)->assertSeeVolt('goals.show');
    }

    public function test_unauthorised_user_going_to_goals_show_route_is_redirected_to_login_route(): void
    {
        $response = $this->get("goals/" . $this->goal->id);

        $response->assertRedirect('/login');
    }

    // TODO: add a feature that prevents users from visiting other users goal route

    public function test_task_card_can_render(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);
        $component = Volt::test('tasks.components.task-card', [$task]);

        $component->assertSee('');
    }

    public function test_newly_created_task_can_be_seen_on_goal_show_component(): void
    {
        $this->actingAs($this->user);
        $task = Task::factory()->make()->toArray();
        // $this->user->goals()->create($goal);
        // $response = $this->actingAs($this->user)->get("goals/" . $this->goal->id);
        $component = Volt::test('goals.show', [$this->goal]);

        $component->assertDontSee($task['title']);
        $component->assertDontSee($task['description']);

        $this->goal->tasks()->create($task);

        $component->dispatch('task-created');

        // TODO: rewrite title assertion and description assertion
        $component->assertSee($task['title'], false);
        // $component->assertSee($task['description']);

        $component->assertStatus(200)->assertSeeVolt('tasks.components.task-card');
    }
}
