<?php

use App\Models\Goal;

use function Livewire\Volt\{state, mount};

state(['title' => 'Sample Goal', 'category' => 'Some Category', 'date' => '28th Dec, 2023', 'goal' => null]);

mount(function () {
    $this->title = $this->goal->title;
    $this->description = $this->goal->description;
    $this->category = $this->goal->category ? $this->goal->category->title : 'No category';
});

$delete = function (Goal $goal) {
    $this->authorize('delete', $goal);

    $goal->delete();

    $this->dispatch('goal-deleted');
    // $this->disableEditing();
};

?>


<div class="max-w-xs  m-6">
    <div class="p-6 space-y-4 bg-gray-800 rounded-lg shadow-lg">
        <div class="flex items-center space-x-4">
            {{-- <div class="p-2 bg-purple-200 rounded-full">
                <!-- Icon from Heroicons (https://heroicons.com/) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a2 2 0 00-2-2h-3v4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15V7a2 2 0 012-2h10a2 2 0 012 2v8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15v4a2 2 0 002 2h3v-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15h16" />
                </svg>
            </div> --}}
            <div class="text-gray-200">
                <p class="text-sm">{{ $category }}</p>
                <h3 class="text-2xl font-semibold ">
                    {{ $title }}
                    {{-- <span class="flex items-center text-sm font-medium text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>
                        122
                    </span> --}}
                </h3>
            </div>
        </div>

        <x-danger-button class="ms-3" wire:click="delete({{ $goal->id }})"
            wire:confirm="Are you sure to delete this goal? '{{ $title }}'?">
            {{ __('Delete') }}
        </x-danger-button>

        <button
            class="w-full px-4 py-2 text-sm text-gray-400 transition duration-300 ease-in-out bg-gray-900 rounded-md hover:bg-indigo-600 hover:text-gray-100">
            View Tasks
        </button>
    </div>
</div>
