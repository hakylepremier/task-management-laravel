<?php

namespace Tests\Feature\Livewire\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
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

    /**
     * A basic feature test example.
     */
    public function test_category_route_can_be_visited_by_auth_user(): void
    {
        $response = $this->actingAs($this->user)->get('/categories');

        $response->assertStatus(200)->assertSeeVolt('category.index');
    }

    public function test_unauthorised_user_going_to_category_is_redirected_to_login_route(): void
    {
        $response = $this->get('/categories');

        $response->assertRedirect('/login');
    }

    public function test_category_index_component_can_render(): void
    {
        $this->actingAs($this->user);

        $component = Volt::test('category.index');

        $component
            ->assertSee(__('Categories'))
            ->assertSeeVolt('category.create');
    }

    public function test_category_index_contains_empty_table(): void
    {
        // $user = $this->createUser();
        $response = $this->actingAs($this->user)->get('/categories');
        // $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee(__('No categories added'));
    }

    public function test_category_index_contains_non_empty_table(): void
    {
        $product = [
            'title' => 'A fake title',
            'description' => fake()->realText(200),
        ];

        $this->user->categories()->create($product);

        $response = $this->actingAs($this->user)->get('/categories');

        $response->assertStatus(200);
        $response
            ->assertSeeVolt('category.index');
        $response->assertDontSee(__('No categories added'));
        $response->assertSee('A fake title');
    }

    public function test_category_can_be_edited()
    {
        $this->actingAs($this->user);
        $category = [
            'title' => fake()->realText(20),
            'description' => fake()->realText(200),
        ];

        $categoryUpdate = [
            'title' => 'An updated title',
            'description' => 'An updated description.',
        ];

        $this->user->categories()->create($category);

        $this->assertDatabaseCount('categories', 1);

        $lastCategory = Category::where($category)->first();

        $component = Volt::test('category.index')
            ->set('category', $lastCategory)
            ->set('title', $categoryUpdate['title'])
            ->set('description', $categoryUpdate['description'])
            ->call('update');

        $this->assertNotEquals($categoryUpdate['title'], $lastCategory->title);
        $this->assertNotEquals($categoryUpdate['description'], $lastCategory->description);
        $this->assertDatabaseMissing('categories', $lastCategory->toArray());
        $this->assertDatabaseHas('categories', $categoryUpdate);
    }

    public function test_category_update_throws_errors_for_invalid_data(): void
    {
        $this->actingAs($this->user);
        $category = [
            'title' => fake()->realText(20),
            'description' => fake()->realText(200),
        ];
        $categoryUpdate = [
            'description' => 1234.37
        ];
        $this->user->categories()->create($category);
        $this->assertDatabaseCount('categories', 1);

        $lastCategory = Category::where($category)->first();

        $component = Volt::test('category.index')
            ->set('category', $lastCategory)
            ->set('description', $categoryUpdate['description'])
            ->call('update');


        $component
            ->assertHasErrors(['title' => 'required', 'description' => 'string']);

        $this->assertDatabaseHas('categories', $lastCategory->toArray());
        $this->assertDatabaseMissing('categories', $categoryUpdate);
    }

    public function test_category_can_be_deleted()
    {
        $this->actingAs($this->user);
        $category = [
            'title' => 'A fake title',
            'description' => fake()->realText(200),
        ];

        $this->user->categories()->create($category);

        $this->assertDatabaseCount('categories', 1);

        $lastCategory = Category::where($category)->first();
        $component = Volt::test('category.index')
            ->call('delete', $lastCategory->id);

        $this->assertDatabaseMissing('categories', $category);
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_category_cannot_be_deleted_by_unautorised_user()
    {
        $this->actingAs($this->user);
        $someUser = $this->createUser();

        $category = [
            'title' => 'A fake title',
            'description' => fake()->realText(200),
        ];

        $someUser->categories()->create($category);

        $this->assertDatabaseCount('categories', 1);

        $lastCateory = Category::where($category)->first();
        $component = Volt::test('category.index')
            ->call('delete', $lastCateory->id);

        $component
            ->assertForbidden();
        //     ->assertHasErrors();

        $this->assertDatabaseHas('categories', $category);
        $this->assertDatabaseCount('categories', 1);
    }
}
