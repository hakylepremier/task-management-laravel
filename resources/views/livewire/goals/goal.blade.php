<?php

use App\Models\Goal;

use function Livewire\Volt\{state, mount};

state([
    'title' => 'Sample Goal',
    'category' => 'Some Category',
    'date' => '28th Dec, 2023',
    'goal' => null,
    'total_tasks_count' => 0,
    'pending_tasks_count' => 0,
    'completed_tasks_count' => 0,
]);

mount(function () {
    $this->title = $this->goal->title;
    $this->description = $this->goal->description;
    $this->total_tasks_count = $this->goal->tasks_count;
    $this->completed_tasks_count = $this->goal->completed_tasks_count;
    $this->pending_tasks_count = $this->total_tasks_count - $this->completed_tasks_count;
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
            <div class="text-gray-200">
                <p class="text-sm">{{ $category }}</p>
                <a href="{{ route('goals.show', $goal) }}" class="block">
                    <h3 class="text-2xl font-semibold ">
                        {{ $title }}
                    </h3>
                    <ul class="text-xs text-gray-500 list-disc pt-4 pl-4">
                        <li>Pending Tasks: {{ $pending_tasks_count }}</li>
                        <li>Completed Tasks: {{ $completed_tasks_count }}</li>
                    </ul>
                </a>
            </div>
        </div>

        <div class="flex flex-end w-full flex-row-reverse border-t-2 border-gray-600 pt-4">

            <x-danger-button class="ms-3" wire:click="delete({{ $goal->id }})"
                wire:confirm="Are you sure to delete this goal? '{{ $title }}'?">
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </div>
</div>
