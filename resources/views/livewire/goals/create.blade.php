<?php

use App\Models\Stage;

use function Livewire\Volt\{rules, state, mount};

$getCategories = fn() => ($this->categories = auth()->user()->categories()->latest()->get());

state([
    'title' => '',
    'description' => null,
    'categories' => $getCategories,
    'category_id' => null,
    'end_date' => null,
    'goalCreateModal' => false,
]);

rules([
    'title' => 'required|string|max:255',
    'description' => 'string|nullable',
    'category_id' => 'integer|nullable',
    'end_date' => 'date|nullable|after:1 minute',
]);

$store = function () use ($getCategories) {
    $validated = $this->validate();
    $stage = ['stage_id' => Stage::firstOrCreate(['title' => 'Processing'])->id];
    // $validated->merge(['stage_id' => Stage::firstOrCreate(['title' => 'Processing'])->id]);
    $goal = array_merge($validated, $stage);
    auth()->user()->goals()->create($goal);

    $this->title = '';
    $this->description = null;
    $this->end_date = null;
    $this->category_id = null;
    $getCategories;

    $this->dispatch('goal-created');
};

// 'title',
// 'description',
// 'image',
// 'category_id',
// 'stage_id',
// 'end_date',

?>

<div>
    <div @click="$wire.goalCreateModal = true"
        class="inline-block px-4 py-2 text-sm text-gray-100 transition duration-300 ease-in-out bg-indigo-600 rounded-md cursor-pointer select-none hover:text-white hover:bg-indigo-500">
        Create Goal
    </div>

    {{-- <x-button icon="m-plus" class="text-gray-900 bg-gray-200 btn-circle btn-sm hover:bg-gray-700 hover:text-gray-200"
        @click="$wire.goalCreateModal = true" /> --}}

    <x-modal wire:model="goalCreateModal" title="Create Goal" x-on:task-created.window="$wire.goalCreateModal = false">
        <x-form wire:submit="store">
            <x-input label="Title" wire:model="title" />
            <x-textarea label="Description" wire:model="description" hint="Max 1000 chars" rows="4" />

            <x-select label="Category" icon="o-squares-2x2" option-value="id" option-label="title"
                placeholder="Select a Category" :options="$categories" wire:model="category_id" />

            <x-datetime label="End Date" wire:model="end_date" icon="o-calendar" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.goalCreateModal = false" />
                <x-button label="Confirm" class="btn-primary" type="submit" spinner="store" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
