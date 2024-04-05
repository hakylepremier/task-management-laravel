<?php

use Illuminate\Database\Eloquent\Builder;
use function Livewire\Volt\{layout, state, on, rules};

layout('layouts.app');

$getGoals = fn() => ($this->goals = auth()
    ->user()
    ->goals()
    ->with('category')
    ->withCount([
        'tasks',
        'tasks as completed_tasks_count' => function (Builder $query) {
            $query->whereHas('state', function (Builder $query) {
                $query->where('title', 'Done');
            });
        },
    ])
    ->latest()
    ->get());

state([
    'goals' => $getGoals,
    'editing' => null,
    'title' => '',
    'description' => '',
    'category' => null,
]);

on([
    'goal-created' => $getGoals,
    'goal-deleted' => $getGoals,
]);
?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Goals') }}
        </h2>
    </x-slot>

    <div x-data="{ openedIndex: false }" class="px-8 pt-6 mx-auto max-w-7xl">
        <livewire:goals.create />
    </div>
    <section class="grid w-full grid-cols-1 px-2 pb-4 mx-auto mb-12 border-red-400 xl:max-w-7xl xl:mb-0 md:grid-cols-4">
        @forelse ($goals as $goal)
            <livewire:goals.goal :goal="$goal" :key="$goal->id">
            @empty
                <div>
                    <p>{{ __('You have not added any goals') }}</p>
                </div>
        @endforelse
    </section>
</div>
