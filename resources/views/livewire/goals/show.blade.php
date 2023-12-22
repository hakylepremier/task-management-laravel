<?php

use App\Models\Goal;
use Carbon\Carbon;

use function Livewire\Volt\{layout, mount, state, on, rules};

layout('layouts.app');

$getTasks = fn() => ($this->tasks = auth()
    ->user()
    ->tasks()
    ->latest()
    ->get());

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
    'state' => ['do' => 'To do', 'progress' => 'In Progress', 'done' => 'Done'],
    'description' => null,
    'category' => null,
    'end_date' => null,
    'num_days_left' => 0,
    'day_warn_color' => 'text-white',
    'tasks' => $getTasks,
    'test' => [
        'title' => 'yes',
        'class' => 'bg-red-500',
    ],
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


    <section class="px-8 py-6 mx-auto text-white max-w-7xl">
        @if ($description)
            <p class="px-8 py-6 bg-indigo-500 rounded-xl">{{ $description }}</p>
        @else
            <p class="text-center">No description provided</p>
        @endif

        @if ($end_date)
            <p class="pt-6"><span class="text-gray-400">Proposed Completion Date:</span>
                {{ Carbon::parse($end_date)->isoFormat('MMMM Do YYYY') }} (<span
                    class="{{ $day_warn_color }}">{{ $num_days_left }}</span>)
            </p>
        @else
            <p class="pt-6 text-center">No completion date given.</p>
        @endif
    </section>

    <div x-data="{ openedIndex: false }" class="px-8 mx-auto max-w-7xl">
        <div x-on:click="isModalOpen = true"
            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-800 uppercase transition duration-150 ease-in-out bg-gray-200 border border-white rounded-md cursor-pointer dark:bg-gray-900 dark:text-white hover:bg-white dark:hover:bg-gray-700 focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            Edit Goal
        </div>
        <livewire:goals.edit :goal="$goal" />
    </div>

    <main class="px-8 mx-auto max-w-7xl flex gap-8 mt-6">
        <div class="w-1/3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">To do</h3>
                <livewire:tasks.create :state="$state['do']" :goal="$goal" />
            </div>
            <section class="flex flex-col gap-4">
                @forelse ($tasks as $task)
                    <x-card class="bg-gray-800" title="{{ $task->title }}" subtitle="{{ $task->description }}">
                        <x-slot:menu>
                            <x-dropdown right>
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-vertical"
                                        class="cursor-pointer bg-transparent border-transparent hover:bg-gray-900 btn-square btn-sm" />
                                </x-slot:trigger>

                                <x-menu-item title="Edit" />
                                <x-menu-item title="Delete" />
                            </x-dropdown>
                        </x-slot:menu>

                    </x-card>
                @empty
                    <p class="text-center">No task available</p>
                @endforelse
            </section>
        </div>
        <div class="w-1/3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">{{ $state['progress'] }}</h3>
                <livewire:tasks.create :state="$state['progress']" :goal="$goal" />
            </div>
            <section class="flex flex-col gap-4">
                @forelse ($tasks as $task)
                    <x-card class="bg-gray-800" title="{{ $task->title }}" subtitle="{{ $task->description }}">
                        <x-slot:menu>
                            <x-dropdown right>
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-vertical"
                                        class="cursor-pointer bg-transparent border-transparent hover:bg-gray-900 btn-square btn-sm" />
                                </x-slot:trigger>

                                <x-menu-item title="Edit" />
                                <x-menu-item title="Delete" />
                            </x-dropdown>
                        </x-slot:menu>

                    </x-card>
                @empty
                    <p class="text-center">No task available</p>
                @endforelse
            </section>
        </div>
        <div class="w-1/3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">{{ $state['done'] }}</h3>
                <livewire:tasks.create :state="$state['done']" />
            </div>
            <section class="flex flex-col gap-4">
                @forelse ($tasks as $task)
                    <x-card class="bg-gray-800" title="{{ $task->title }}" subtitle="{{ $task->description }}">
                        <x-slot:menu>
                            <x-dropdown right>
                                <x-slot:trigger>
                                    <x-button icon="o-ellipsis-vertical"
                                        class="cursor-pointer bg-transparent border-transparent hover:bg-gray-900 btn-square btn-sm" />
                                </x-slot:trigger>

                                <x-menu-item title="Edit" />
                                <x-menu-item title="Delete" />
                            </x-dropdown>
                        </x-slot:menu>

                    </x-card>
                @empty
                    <p class="text-center">No task available</p>
                @endforelse
            </section>
        </div>
    </main>



</div>
