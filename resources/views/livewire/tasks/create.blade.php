<?php

use App\Models\Goal;
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
    'taskCreateModal' => false,
]);

rules([
    'title' => 'required|string|max:255',
    'description' => 'string|nullable',
    'state_id' => 'integer',
    'goal_id' => 'integer|nullable',
    'user_id' => 'integer|nullable',
]);

uses([Toast::class]);

$store = function () {
    $validated = $this->validate();
    if ($this->goal) {
        $test = 'goal';
        $this->goal?->tasks()->create($validated);
    } else {
        $test = 'user';
        auth()
            ->user()
            ->tasks()
            ->create($validated);
    }

    $this->toast(
        type: 'success',
        title: 'Task created Successfully',
        description: null, // optional (text)
        position: 'toast-top toast-end',
        icon: 'o-information-circle',
        timeout: 2000,
    );

    $this->title = '';
    $this->description = null;
    // $this->end_date = null;
    // $this->category_id = null;

    $this->dispatch('task-created');
};

mount(function ($state, ?Goal $goal) {
    if ($goal->title !== null) {
        $this->goal = $goal;
        $this->goal_id = $goal->id;
        $this->user_id = null;
    } else {
        $this->goal_id = null;
        $this->user_id = auth()->id();
    }
    // dump($state);
    // dd($goal);

    $this->state = State::firstOrCreate(['title' => $state]);
    $this->state_id = $this->state->id;
});

?>

<div>
    <x-button icon="m-plus" class="btn-circle btn-sm bg-gray-200 text-gray-900 hover:bg-gray-700 hover:text-gray-200"
        @click="$wire.taskCreateModal = true" />

    <x-modal wire:model="taskCreateModal" title="Create Task" x-on:task-created.window="$wire.taskCreateModal = false">

        <x-form wire:submit="store">
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
                <x-button label="Cancel" @click="$wire.taskCreateModal = false" />
                <x-button label="Confirm" class="btn-primary" type="submit" spinner="store" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
