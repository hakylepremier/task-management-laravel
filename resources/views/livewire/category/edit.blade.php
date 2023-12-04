<?php

use function Livewire\Volt\{rules, state, mount};

state(['category', 'title', 'description']);

rules(['title' => 'required|string|max:255', 'description' => 'string']);

mount(function () {
    $this->title = $this->category->title;
    $this->description = $this->category->description;
});

?>

<div>
    <form wire:submit="update">
        <td>
            {{-- <x-input-label for="title" :value="__('Title')" /> --}}
            <x-text-input wire:model="title" :value="$title" id="title" class="block w-full mt-1" type="text"
                name="title" placeholder="{{ __('Title') }}" required />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </td>

        <td>
            <textarea wire:model="description" placeholder="{{ __('Description') }}"
                class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ $description }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </td>

        <td>{{ $title }}</td>

        <td></td>

        <td>
            <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
            {{-- <x-primary-button wire:click.prevent="cancel" class="mt-4">{{ __('Cancel') }}</x-primary-button> --}}
            <button wire:click.prevent="disableEditing"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md dark:bg-gray-900 dark:text-white hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-200 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">{{ __('Cancel') }}</button>
        </td>
    </form>
</div>
