<?php

use App\Models\Category;

use function Livewire\Volt\{layout, state, on, rules};

$getCategories = fn() => ($this->categories = auth()
    ->user()
    ->categories()
    ->latest()
    ->get());

state([
    'categories' => $getCategories,
    'editing' => null,
    'title' => '',
    'description' => '',
    'category' => null,
]);

rules(['title' => 'required|string|max:255', 'description' => 'string']);

$disableEditing = function () {
    $this->editing = null;
    $this->category = null;

    $this->title = '';
    $this->description = '';

    return $this->getCategories();
};

$delete = function (Category $category) {
    $this->authorize('delete', $category);

    $category->delete();

    $this->getCategories();
    $this->disableEditing();
};

$edit = function (Category $category) {
    $this->editing = $category;
    $this->category = $category;

    $this->title = $this->category->title;
    $this->description = $this->category->description;

    $this->getCategories();
};

$update = function () {
    $this->authorize('update', $this->category);

    $validated = $this->validate();

    $this->category->update($validated);

    $this->disableEditing();
};

$cancel = fn() => $this->dispatch('category-edit-canceled');

layout('layouts.app');

on(['category-created' => $getCategories, 'category-edited' => $disableEditing, 'category-edit-canceled' => $disableEditing]);

?>

<div class="relative" x-data="{ 'isModalOpen': false }" x-on:keydown.escape="isModalOpen=false;$wire.disableEditing()">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <section class="px-4 py-6 mx-auto max-w-7xl">
        <livewire:category.create />
    </section>

    <aside class="absolute top-0 left-0 z-10 flex items-center justify-center w-full h-full bg-sky-950/50 "
        x-show="isModalOpen" x-cloak>
        <article class="p-6 bg-gray-800 border border-white rounded-2xl ">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Category') }}
                </h2>
            </header>
            <form wire:submit="update" x-show="isModalOpen" x-on:click.away="$wire.disableEditing();isModalOpen = false"
                x-cloak x-transition>
                <div class="w-96 ">
                    {{-- <x-input-label for="title" :value="__('Title')" /> --}}
                    <x-text-input wire:model="title" id="title" class="block w-full mt-1" type="text"
                        name="title" placeholder="{{ __('Title') }}" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <textarea wire:model="description" placeholder="{{ __('Description') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <x-primary-button class="mt-4" x-on:click="isModalOpen = false">{{ __('Edit') }}</x-primary-button>
                <button wire:click.prevent="disableEditing" x-on:click="isModalOpen = false"
                    class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-gray-800 border border-gray-600 rounded-md dark:bg-gray-900 dark:text-white hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-200 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">{{ __('Cancel') }}</button>
            </form>
        </article>
    </aside>

    <section class="w-full px-4 pb-4 mx-auto mb-12 border-red-400 xl:max-w-7xl xl:mb-0">
        <div
            class="relative flex flex-col w-full min-w-0 mb-6 text-gray-400 break-words bg-gray-800 border-red-400 rounded shadow-lg">
            <div class="px-4 py-3 mb-0 border-0 border-red-400 rounded-t">
                <div class="flex flex-wrap items-center ">
                    <div class="relative flex-1 flex-grow w-full max-w-full px-4">
                        <h3 class="text-base font-semibold text-blueGray-700">{{ __('Categories') }}</h3>
                    </div>
                    <div class="relative flex-1 flex-grow w-full max-w-full px-4 text-right">
                        <x-primary-button>{{ __('See All') }}</x-primary-button>
                    </div>
                </div>
            </div>

            <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse border-red-400">
                    <thead class="text-gray-100 border-0 border-red-400">
                        <tr>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-gray-700 border-solid bg-blueGray-50 text-blueGray-500 whitespace-nowrap">
                                {{ __('Title') }}
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-gray-700 border-solid bg-blueGray-50 text-blueGray-500 whitespace-nowrap">
                                {{ __('Description') }}
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-gray-700 border-solid bg-blueGray-50 text-blueGray-500 whitespace-nowrap">
                                {{ __('Number of Goals') }}
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-gray-700 border-solid bg-blueGray-50 text-blueGray-500 whitespace-nowrap">
                                {{ _('Updated At') }}
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-gray-700 border-solid bg-blueGray-50 text-blueGray-500 whitespace-nowrap">
                                {{ _('Actions') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td
                                    class="p-4 px-6 text-xs text-left align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap text-blueGray-700 ">
                                    {{ $category->title }}
                                </td>
                                <td class="max-w-sm p-4 px-6 text-xs align-middle border-t-0 border-l-0 border-r-0 ">
                                    {{ $category->description }}
                                </td>
                                <td
                                    class="p-4 px-6 text-xs border-t-0 border-l-0 border-r-0 align-center whitespace-nowrap">
                                    3
                                </td>
                                <td
                                    class="p-4 px-6 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap">
                                    <i class="mr-4 fas fa-arrow-up text-emerald-500"></i>
                                    {{ $category->updated_at }}
                                </td>
                                <td
                                    class="p-4 px-6 text-xs border-t-0 border-l-0 border-r-0 align-center whitespace-nowrap">
                                    @if (!$category->is($editing))
                                        <x-secondary-button wire:click="edit({{ $category }})"
                                            x-on:click="isModalOpen = true">
                                            {{ __('Edit') }}
                                        </x-secondary-button>

                                        <x-danger-button class="ms-3" wire:click="delete({{ $category->id }})"
                                            wire:confirm="Are you sure to delete this category '{{ $category->title }}'?">
                                            {{ __('Delete') }}
                                        </x-danger-button>
                                    @else
                                        <button wire:click.prevent="disableEditing"
                                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-gray-800 border border-gray-600 rounded-md dark:bg-gray-900 dark:text-white focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                            {{ __('Cancel Edit') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center">{{ __('No categories added') }}</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </section>

</div>
