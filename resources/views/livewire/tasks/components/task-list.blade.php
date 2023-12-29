<?php

use App\Models\Goal;
use App\Models\Task;
use App\Models\State;

use function Livewire\Volt\{state, mount, on};

state([
    'tasks' => [],
    'state' => null,
    'states' => [],
    'goal' => null,
]);

mount(function (Goal $goal, State $state, $states) {
    $this->goal = $goal;
    $this->state = $state;
    $test = $state->id;
    $this->tasks = $goal
        ->tasks()
        ->where('state_id', $state->id)
        ->with('state')
        ->with('goal')
        ->latest()
        ->get();

    $this->states = $states;
    $test2 = $this->tasks->toArray();
    $test3 = 1;
});

on([
    'task-created' => function () {
        $this->goal = $this->goal->refresh();
        $this->tasks = $this->goal
            ->tasks()
            ->where('state_id', $this->state->id)
            ->with('state')
            ->with('goal')
            ->latest()
            ->get();
    },
    'task-deleted' => function () {
        $this->goal = $this->goal->refresh();
        $this->tasks = $this->goal
            ->tasks()
            ->where('state_id', $this->state->id)
            ->with('state')
            ->with('goal')
            ->latest()
            ->get();
    },
]);

?>

<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">{{ $state->title }}</h3>
        <livewire:tasks.create :state="$state->title" :goal="$goal" />
    </div>
    <section class="flex flex-col gap-4">
        @forelse ($tasks as $task)
            <livewire:tasks.components.task-card :task="$task" :states="$states" wire:key="{{ $task->id }}" />
        @empty
            <p class="text-center">No task available</p>
        @endforelse
    </section>
</div>
