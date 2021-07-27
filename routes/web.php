<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\User\UserIndex;
use App\Http\Livewire\Item\ItemIndex;
use App\Http\Livewire\Source\SourceIndex;
use App\Http\Livewire\Inventory\InventoryIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/items', ItemIndex::class)->name('items.index');
    Route::get('/sources', SourceIndex::class)->name('sources.index');
    Route::get('/inventories', InventoryIndex::class)->name('inventories.index');
});
