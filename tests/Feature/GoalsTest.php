<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoalsTest extends TestCase
{
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

    /**
     * A basic feature test example.
     */
    public function test_goals_route_can_be_visited_by_auth_user(): void
    {
        $response = $this->actingAs($this->user)->get('/goals');

        $response->assertStatus(200);
    }

    public function test_unauthorised_user_going_to_goals_is_redirected_to_login_route(): void
    {
        $response = $this->get('/goals');

        $response->assertRedirect('/login');
    }
}
