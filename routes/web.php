<?php

use App\Enums\PanelId;
use App\Filament\Admin\Resources\Inventory\StockTransfers\Pages\EditStockTransfer;
use App\Filament\Outlet\Pages\Pos;
use App\Models\Inventory\InventoryLedger;
use App\Models\Inventory\StockTransfer;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
