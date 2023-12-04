<?php

namespace Tests\Feature\Livewire\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CategoryCreateTest extends TestCase
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

    public function test_category_create_component_can_render(): void
    {
        $component = Volt::test('category.create');

        $component->assertSee(__('Create Category'));
    }

    public function test_category_create_can_create_a_new_category(): void
    {
        $title = 'A fake title';
        $description = fake()->realText(200);
        $category = [
            'title' => $title,
            'description' => $description
        ];

        $this->actingAs($this->user);
        $component = Volt::test('category.create')
            ->set('title', $category['title'])
            ->set('description', $category['description']);
        $component->call('store');

        // $component->actingAs($this->user)->call('store');

        $lastCategory = Category::latest()->first();

        $component
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', $category);
        $this->assertEquals($category['title'], $lastCategory->title);
        $this->assertEquals($category['description'], $lastCategory->description);
    }

    public function test_category_create_throws_errors_for_invalid_data(): void
    {
        $category = [
            'description' => 1234.67
        ];

        $this->actingAs($this->user);
        $component = Volt::test('category.create')
            ->set('description', $category['description']);
        $component->call('store');

        // $lastCategory = Category::latest()->first();

        $component
            ->assertHasErrors(['title', 'description']);

        // $this->assertDatabaseHas('categories', $category);
        // $this->assertEquals($category['title'], $lastCategory->title);
        // $this->assertEquals($category['description'], $lastCategory->description);
    }
}
