<?php

use function Livewire\Volt\{state};

?>

<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create Goal') }}
        </h2>
    </header>
    <form wire:submit="store">
        <div>
            {{-- <x-input-label for="title" :value="__('Title')" /> --}}
            <x-text-input wire:model="title" id="title" class="block w-full mt-1" type="text" name="title"
                placeholder="{{ __('Title') }}" required />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <textarea wire:model="description" placeholder="{{ __('Description') }}"
                class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
    </form>
</div>
