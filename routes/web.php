<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'home');

Volt::route('/goals', 'goals.index')->middleware(['auth', 'verified'])->name('goals');

Volt::route('/goals/{goal}', 'goals.show')->middleware(['auth', 'verified'])->name('goals.show');

Volt::route('/categories', 'category.index')->middleware(['auth', 'verified'])->name('categories');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
