<?php

use App\Enums\PanelId;
use Filament\Facades\Filament;
use App\Filament\Outlet\Pages\Pos;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\Inventory\InventoryLedger;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');

    return back();
})->name('optimize');

Route::get('/optimize/clear', function () {
    $output = Artisan::call('optimize:clear');

    return back();
})->name('optimize:clear');

// dd(Filament::getUrl(), Filament::getTenant());

// Route::get( . '/pos/bootstrap', [Pos::class, 'bootstrap'])->name('pos.bootstrap');
