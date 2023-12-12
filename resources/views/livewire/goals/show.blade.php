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
        $this->num_days_left =  $days == 0 ? 'Tomorrow' : $remark . " from now.";
        $this->day_warn_color = 'text-green-400';
    }
};

mount(function (Goal $goal) {

    $this->goal = $goal;

    // $this->dateRemark('2024-01-01');
    $this->dateRemark($goal->end_date);
});


state([
    'goal' => null,
    'num_days_left' => 0,
    'day_warn_color' => 'text-white',
]);

?>

<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ $goal->title }}
            </h2>
            <h3 class="text-gray-400">Category: {{ $goal->category ? $goal->category->title : 'No category added' }}</h3>
        </div>
    </x-slot>

    <section class="text-white px-8 py-6 mx-auto max-w-7xl">
        @if ($goal->description)
        <p class="bg-indigo-500 px-8 py-6 rounded-xl">{{ $goal->description }}</p>
        @else
        <p class="text-center">No description provided</p>
        @endif

        @if ($goal->end_date)
        <p class="py-6"><span class="text-gray-400">Proposed Completion Date:</span> {{ Carbon::parse($goal->end_date)->isoFormat('MMMM Do YYYY') }} (<span class="{{ $day_warn_color }}">{{ $num_days_left }}</span>)</p>
        @else
        <p class="text-center">No completion date given.</p>
        @endif
    </section>
</div>