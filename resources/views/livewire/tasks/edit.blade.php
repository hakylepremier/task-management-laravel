<?php

use App\Models\Goal;
use App\Models\Task;
use App\Models\State;
use Mary\Traits\Toast;

use function Livewire\Volt\{state, mount, rules, uses};

state([
    'title' => '',
    'description' => null,
    'goal' => null,
    'goal_id' => null,
    'state' => null,
    'state_id' => null,
    'user_id' => null,
    'task' => null,
    'taskUpdateModal' => false
]);

// state(['taskUpdateModal' => false])->reactive();

rules([
    'title' => 'required|string|max:255',
    'description' => 'string|nullable',
    'state_id' => 'integer',
    'goal_id' => 'integer|nullable',
    'user_id' => 'integer|nullable',
]);

uses([Toast::class]);

$update = function () {
    $validated = $this->authorize('update', $this->task);
    $validated = $this->validate();

    if ($this->description === '') {
        $validated['description'] = null;
    }

    $this->task->update($validated);

    $this->dispatch('task-updated');
    $this->success('Task Updated successfully.');
};

mount(function (Task $task, $open = false) {
    // $test = $task->toArray();
    $this->task = $task;
    $this->title = $task->title;
    $this->description = $task->description;
    $this->state = $task->state;
    $this->goal = $task->goal;
    $this->state_id = $this->state->id;
    $this->goal_id = $task->goal_id;
    $this->user_id = $task->user_id;
    $this->taskUpdateModal = $open;
});

?>

<div>
    <x-menu-item title="Edit" @click.stop="$wire.taskUpdateModal = true" />

    <x-modal wire:model="taskUpdateModal" title="Edit Task" x-on:task-updated.window="$wire.taskUpdateModal = false" @click.stop=''>

        <x-form wire:submit="update">
            @if ($goal_id === null)
            <p class="text-purple-400">This task is not associated with any of your goals.</p>
            @else
            <p>Goal: {{ $goal->title }}</p>
            @endif
            <x-input label="Title" wire:model="title" />
            <x-textarea label="Description" wire:model="description" hint="Max 1000 chars" rows="4" />
            <x-input value="{{ $state->title }}" wire:model="state_id" class="hidden" readonly />
            <x-input label="State" value="{{ $state->title }}" readonly />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.taskUpdateModal = false" />
                <x-button label="Confirm" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>