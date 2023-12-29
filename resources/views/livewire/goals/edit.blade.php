<?php

use App\Models\Goal;
use App\Models\Stage;
use Mary\Traits\Toast;
use Carbon\Carbon;

use function Livewire\Volt\{rules, state, mount, uses};

uses([Toast::class]);

$getCategories = fn() => ($this->categories = auth()
    ->user()
    ->categories()
    ->latest()
    ->get());

$chosenCategory = function () {
    if ($this->category_id === '') {
        $this->category_id === null;
    }
};

state([
    'goal' => '',
    'title' => '',
    'description' => null,
    'categories' => $getCategories,
    'category_id' => null,
    'end_date' => null,
    'goalEditModal' => false,
]);

rules([
    'title' => 'required|string|max:255',
    'description' => 'string|nullable',
    'category_id' => 'integer|nullable',
    'end_date' => 'date|nullable|after_or_equal:today',
    // 'end_date' => 'date|nullable',
]);

$update = function () use ($getCategories) {
    $validated = $this->authorize('update', $this->goal);
    $validated = $this->validate();
    $see = $validated;

    // $yes = ;
    if ($this->category_id === '') {
        $validated['category_id'] = null;
    }
    if ($this->end_date === '') {
        $validated['end_date'] = null;
    }
    if ($this->description === '') {
        $validated['description'] = null;
    }
    $another = $validated;

    $this->goal->update($validated);

    $this->dispatch('goal-updated');
    $this->success('Goal Updated successfully.');
};

mount(function (Goal $goal) {
    $this->goal = $goal;

    $this->title = $goal ? $goal->title : null;
    $this->description = $goal ? $goal->description : null;
    $this->category_id = $goal->category ? $goal->category->id : null;
    $this->end_date = $goal->end_date ? Carbon::parse($goal->end_date)->format('Y-m-d') : null;
});
?>
<div>
    <div @click="$wire.goalEditModal = true"
        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-800 uppercase transition duration-150 ease-in-out bg-gray-200 border border-white rounded-md cursor-pointer dark:bg-gray-900 dark:text-white hover:bg-white dark:hover:bg-gray-700 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
        Edit Goal
    </div>

    <x-modal wire:model="goalEditModal" title="Edit Goal" x-on:goal-updated.window="$wire.goalEditModal = false">
        <form wire:submit="update" class="flex flex-col gap-3">
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="title" id="title" class="block w-full mt-2" type="text" name="title"
                    required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea wire:model="description"
                    class="w-full mt-2 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <div>
                <x-select label="Categories" :options="$categories" :optionLabel="'title'" wire:model="category_id" />
            </div>
            <div>
                <x-input-label for="end_date" :value="__('End Date')" />
                <input wire:model="end_date" id="end_date" type="date" value="" name="end_date"
                    class="mt-2" />
                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
            </div>
            <div>
                <x-button label="Confirm" class="btn-primary" type="submit" spinner="update" />
                <x-button label="Cancel" @click="$wire.taskCreateModal = false" />
            </div>
            {{-- <x-button class="mt-4">{{ __('Save') }}</x-button>
            <button wire:click.prevent="" @click="$wire.goalEditModal = false"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-gray-800 border border-gray-600 rounded-md dark:bg-gray-900 dark:text-white hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-200 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">{{ __('Cancel') }}</button> --}}
        </form>
    </x-modal>
</div>
