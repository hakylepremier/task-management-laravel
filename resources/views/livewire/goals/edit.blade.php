<?php

use App\Models\Goal;
use App\Models\Stage;
use Carbon\Carbon;

use function Livewire\Volt\{rules, state, mount};

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
};

mount(function (Goal $goal) {
    $this->goal = $goal;

    $this->title = $goal ? $goal->title : null;
    $this->description = $goal ? $goal->description : null;
    $this->category_id = $goal->category ? $goal->category->id : null;
    $this->end_date = $goal->end_date ? Carbon::parse($goal->end_date)->format('Y-m-d') : null;
});
?>
<aside class="absolute top-0 left-0 z-10 flex items-center justify-center w-full h-full bg-sky-950/50 "
    x-show="isModalOpen" x-cloak>
    <article class="p-6 bg-gray-800 border border-white rounded-2xl shrink basis-[30rem]">

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Goal') }}
            </h2>
        </header>
        <form wire:submit="update" x-show="isModalOpen" x-on:click.away="isModalOpen = false"
            x-on:goal-updated.window="isModalOpen = false" x-cloak x-transition>
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
                <select id="category" wire:model.lazy="category_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" @if ($category_id === null) selected @endif>No category</option>
                    @forelse ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @empty
                        <div></div>
                    @endforelse
                </select>
            </div>
            <div>
                <x-input-label for="end_date" :value="__('End Date')" />
                <input wire:model="end_date" id="end_date" type="date" value="" name="end_date" />
                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
            </div>
            <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
            <button wire:click.prevent="" x-on:click="isModalOpen = false"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-gray-800 border border-gray-600 rounded-md dark:bg-gray-900 dark:text-white hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-200 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">{{ __('Cancel') }}</button>
        </form>
    </article>
</aside>
