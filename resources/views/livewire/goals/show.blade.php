<?php

use App\Models\Goal;
use Carbon\Carbon;

use function Livewire\Volt\{layout, mount, state, on, rules};

layout('layouts.app');

$dateRemark = function ($my_end_date) {
    $date = Carbon::parse($my_end_date);
    $days = $date->diffInDays(now());

    if ($days == 1) {
        $remark = $days . ' day';
    } else {
        $remark = $days . ' days';
    }

    if ($date->isPast()) {
        $this->num_days_left = $days == 0 ? 'Today' : $remark . ' overdue.';
        $this->day_warn_color = 'text-red-500';
    } else {
        $this->num_days_left = $days == 0 ? 'Tomorrow' : $remark . ' from now.';
        $this->day_warn_color = 'text-green-400';
    }
};

mount(function (Goal $goal) {
    $this->goal = $goal;
    $this->title = $goal->title;
    $this->description = $goal->description ? $goal->description : null;
    $this->category = $goal->category ? $goal->category : null;
    $this->end_date = $goal->end_date ? Carbon::parse($goal->end_date)->format('Y-m-d') : null;

    // $this->dateRemark('2024-01-01');
    $this->dateRemark($goal->end_date);
});

state([
    'goal' => null,
    'title' => '',
    'description' => null,
    'category' => null,
    'end_date' => null,
    'num_days_left' => 0,
    'day_warn_color' => 'text-white',
]);

on([
    'goal-updated' => function () {
        // $getGoals;
        // dd($this->goal->refresh()->toArray());
        $this->goal = $this->goal->refresh();
        // $refresh;
        // dd($this->goal->category->toArray());

        $this->title = $this->goal->title;
        $this->description = $this->goal->description ? $this->goal->description : null;
        $this->category = $this->goal->category ? $this->goal->category->refresh() : null;
        $this->end_date = $this->goal->end_date ? Carbon::parse($this->goal->end_date)->format('Y-m-d') : null;
        $this->dateRemark($this->end_date);
        // dd($this->goal->toArray());
    },
]);

?>

<div class="relative" x-data="{ 'isModalOpen': false }" x-on:keydown.escape="isModalOpen=false">
    <header class="bg-white shadow dark:bg-gray-800">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ $title }}
                </h2>
                <h3 class="text-gray-400">Category: {{ $category ? $category->title : 'No category added' }}</h3>
            </div>
        </div>
    </header>


    <section class="text-white px-8 py-6 mx-auto max-w-7xl">
        @if ($description)
            <p class="bg-indigo-500 px-8 py-6 rounded-xl">{{ $description }}</p>
        @else
            <p class="text-center">No description provided</p>
        @endif

        @if ($end_date)
            <p class="pt-6"><span class="text-gray-400">Proposed Completion Date:</span>
                {{ Carbon::parse($end_date)->isoFormat('MMMM Do YYYY') }} (<span
                    class="{{ $day_warn_color }}">{{ $num_days_left }}</span>)
            </p>
        @else
            <p class="text-center pt-6">No completion date given.</p>
        @endif
    </section>

    <div x-data="{ openedIndex: false }" class="px-8 mx-auto max-w-7xl">
        <div x-on:click="isModalOpen = true"
            class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-900 border border-white rounded-md font-semibold text-xs text-gray-800 dark:text-white uppercase tracking-widest hover:bg-white dark:hover:bg-gray-700 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 cursor-pointer">
            Create Goal
        </div>
        <livewire:goals.edit :goal="$goal" />
    </div>
</div>
