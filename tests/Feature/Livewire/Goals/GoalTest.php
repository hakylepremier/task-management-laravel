<?php

namespace Tests\Feature\Livewire\Goals;

use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalTest extends TestCase
{
    public function test_goal_component_can_render(): void
    {
        $component = Volt::test('goals.goal');

        $component->assertSee('Sample Goal');
    }
}
