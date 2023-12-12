<?php

namespace Tests\Feature\Livewire\Goals;

use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalIndexTest extends TestCase
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

    public function test_goals_page_component_can_render(): void
    {
        $this->actingAs($this->user);

        $component = Volt::test('goals.index');

        $component
            ->assertSeeVolt('goals.index');
    }

    public function test_goals_route_can_be_visited_by_auth_user(): void
    {
        $response = $this->actingAs($this->user)->get('/goals');

        $response->assertStatus(200)->assertSeeVolt('goals.index');
    }

    public function test_unauthorised_user_going_to_goals_is_redirected_to_login_route(): void
    {
        $response = $this->get('/goals');

        $response->assertRedirect('/login');
    }

    public function test_goal_index_contains_empty_table(): void
    {
        // $user = $this->createUser();
        $response = $this->actingAs($this->user)->get('/goals');
        // $response = $this->get('/goalss');
        $response->assertStatus(200);
        $response->assertSee(__('You have not added any goals'));
    }

    public function test_goals_index_contains_non_empty_table(): void
    {
        $fakeCategory = [
            'title' => 'A fake category',
            'description' => fake()->realText(200),
        ];

        $this->user->categories()->create($fakeCategory);

        $category = $this->user->categories()->first();

        $goals = [
            'title' => 'A fake title',
            'description' => 'A fake description for a goal',
            'image' => null,
            'category_id' => $category->id,
            'stage_id' => Stage::factory()->create(['title' => 'Processing'])->id,
            'end_date' => fake()->date(),
        ];

        $this->user->goals()->create($goals);

        $response = $this->actingAs($this->user)->get('/goals');

        $response->assertStatus(200);
        $response
            ->assertSeeVolt('goals.index')
            ->assertSeeVolt('goals.goal');
        $this->assertDatabaseHas('goals', $goals);
        $response->assertDontSee(__('You have not added any goals'));
        $response->assertSee('A fake title');
        $response->assertSee('A fake category');
    }
}
