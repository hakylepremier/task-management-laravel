<?php

use App\Models\Task;
use App\Models\State;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Mary\Traits\Toast;

use function Livewire\Volt\{state, mount, uses, on};

uses([Toast::class]);

state([
    'task' => null,
    'date' => null,
    'updated_at' => null,
    'test' => 'A',
    'states' => [],
    'state' => null,
    'state_id' => null,
    'taskUpdateModal' => false,
    // 'state' => [
    //     'do' => State::firstOrNew(['title' => 'To do']),
    //     'progress' => State::firstOrNew(['title' => 'In Progress']),
    //     'done' => State::firstOrNew(['title' => 'Done']),
    // ],
]);

mount(function (Task $task, $states) {
    $this->task = $task;
    $this->updated_at = $task->updated_at;
    $this->date = Carbon::parse($task->updated_at)->diffForHumans(now(), [
        'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
        'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS,
    ]);

    $this->state = $task->state;
    $this->state_id = $task->state->id;
    // $this->states = State::whereIn('title', ['To do', 'In progress', 'Done'])->get();
    $this->states = $states;
});

$toggleModal = function () {
    // dd('First Test');
    $this->dispatch('edit-modal-toggle', task_id: $this->task->id);
};

$delete = function (Task $task) {
    $this->authorize('delete', $task);

    $task->delete();

    $this->dispatch('task-deleted');

    $this->success('Task Deleted successfully.');
    // $this->disableEditing();
};

on([
    'task-created' => 'render',
    'task-updated' => function () {
        $this->task = $this->task->refresh();
    },
]);

?>

<div>
    <x-card class="bg-gray-800" title="{{ $task->title }}" subtitle="{{ $task->description }}">
        <x-slot:menu>
            <x-dropdown class="w-7" spinner="toggleModal" right>
                <x-slot:trigger>
                    <x-button icon="o-ellipsis-vertical"
                        class="bg-transparent border-transparent cursor-pointer hover:bg-gray-900 btn-square btn-sm" />
                </x-slot:trigger>

                <x-menu-sub title="Change State" class="w-8">
                    <form>
                        <div class="flex gap-4">
                            <div class="flex flex-col join join-vertical">
                                @foreach ($states as $singleState)
                                    <input type="radio" :key="$singleState" id="{{ $singleState->id }}"
                                        value="{{ $singleState->id }}" wire:model="state_id"
                                        class="capitalize join-item btn input-bordered input bg-base-200s">
                                @endforeach
                            </div>
                            <div class="flex flex-col gap-2 justify-evenly basis-full">
                                @foreach ($states as $singleState)
                                    <label for="{{ $singleState->id }}">{{ $singleState->title }}</label>
                                @endforeach
                            </div>
                        </div>
                        <x-button label="Change" class="mt-2 btn-primary" type="submit" spinner="store" />
                    </form>
                    {{-- <x-radio label="Select one" :options="$states" wire:model="selectedUser" option-label="title"
                        class="flex flex-col" /> --}}

                    {{-- <x-menu-item title="Wifi" icon="o-wifi" />
                    <x-menu-item title="Archives" icon="o-archive-box" /> --}}
                </x-menu-sub>

                {{-- <livewire:tasks.components.task-delete /> --}}
                {{-- <x-menu-item title="Edit" @click="$wire.taskUpdateModal = true" spinner /> --}}
                {{-- <x-button label="Cancel" wire:click.stop="toggleModal" spinner /> --}}
                {{-- <x-button label="Confirm" class="btn-primary" @click="$wire.toggleModal" spinner="toggleModal"
                    @click.stop="" /> --}}
                <livewire:tasks.edit :task="$task->id" />
                <x-menu-item title="Delete" wire:click="delete({{ $task->id }})"
                    wire:confirm="Are you sure to delete the task titled '{{ $task->title }}'"
                    wire:loading.attr="disabled" class="bg-red-500" />
            </x-dropdown>
        </x-slot:menu>
        <div class="flex items-center justify-between">
            <x-badge value="{{ $date }}" class="badge-neutral" />
            <button class="flex items-center">
                <div
                    class="gap-1 text-gray-200 badge badge-primary hover:bg-gray-200 hover:text-gray-700 hover:border-gray-700">
                    <x-icon name="s-play" class="w-4 h-4 " />
                    Start
                </div>
            </button>
        </div>
        {{-- <livewire:tasks.edit :task="$task->id" :$taskUpdateModal /> --}}
    </x-card>
</div>
