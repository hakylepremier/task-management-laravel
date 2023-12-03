<?php

namespace Tests\Feature\Livewire\Goals;

use Livewire\Volt\Volt;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_goals_page_component_can_render(): void
    {
        $component = Volt::test('goals.index');

        $component->assertSee('');
    }
}
