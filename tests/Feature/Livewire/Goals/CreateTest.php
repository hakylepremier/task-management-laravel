<?php

namespace Tests\Feature\Livewire\Goals;

use Livewire\Volt\Volt;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_it_can_render(): void
    {
        $component = Volt::test('goals.create');

        $component->assertSee('');
    }
}
