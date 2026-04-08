<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');

    return back();
})->name('optimize');

Route::get('/optimize/clear', function () {
    $output = Artisan::call('optimize:clear');

    return back();
})->name('optimize:clear');

Route::get('/terminal/', redirect('/terminal'));

Route::get('/terminal/{any?}/', function () {
    $html = file_get_contents(public_path('pos-terminal/index.html'));
    return $html;
})->where('any', '.*');
