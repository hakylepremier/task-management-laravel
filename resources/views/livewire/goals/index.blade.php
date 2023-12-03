<?php

use function Livewire\Volt\{layout, state};

layout('layouts.app');

?>

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Goals') }}
        </h2>
    </x-slot>

    <livewire:goals.goal>
</div>
