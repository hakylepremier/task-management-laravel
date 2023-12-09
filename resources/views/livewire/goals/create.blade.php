<?php

use App\Models\Stage;

use function Livewire\Volt\{rules, state};

$getCategories = fn() => ($this->categories = auth()
    ->user()
    ->categories()
    ->latest()
    ->get());

state([
    'title' => '',
    'description' => null,
    'categories' => $getCategories,
    'category_id' => null,
    'end_date' => null,
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
    auth()
        ->user()
        ->goals()
        ->create($goal);

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
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create Goal') }}
        </h2>
    </header>
    <form wire:submit="store">
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input wire:model="title" id="title" class="block w-full mt-1" type="text" name="title"
                required />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea wire:model="description"
                class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="category" :value="__('Categories')" />
            <select id="category" wire:model="category_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>No category</option>
                @forelse ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @empty
                    <div></div>
                @endforelse
            </select>
        </div>
        <div>
            <x-input-label for="end_date" :value="__('End Date')" />
            <input wire:model="end_date" id="end_date" type="date" value="{{ now() }}" name="end_date"
                required />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>
        <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
    </form>
</div>
