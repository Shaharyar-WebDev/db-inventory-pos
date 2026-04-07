<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Api\Pos\PosAuthController;
use App\Http\Controllers\Api\Pos\PosBootstrapController;
use App\Http\Controllers\Api\Pos\PosSaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('pos')->group(function () {

    // Public
    Route::post('/login', [PosAuthController::class, 'login']);

    // Protected
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::middleware([EnsureOutletAccess::class])->group(function () {
            Route::get('/getLoggedInUser', [PosAuthController::class, 'getLoggedInUser']);

            Route::get('/getProducts', [ProductController::class, 'index']);

            Route::get('/getCustomers', [CustomerController::class, 'index']);

            Route::post('/customers', [CustomerController::class, 'store']);

            Route::get('/getAccounts', [AccountController::class, 'index']);

            Route::post('/logout',    [PosAuthController::class, 'logout']);
            Route::get('/bootstrap',  [PosBootstrapController::class, 'index']);
            Route::post('/sales',     [PosSaleController::class, 'store']);
        });
        
        Route::get('/getLoggedInUserOutlets', [PosAuthController::class, 'getLoggedInUserOutlets']);
    });

});


class EnsureOutletAccess
{
    public function handle($request, Closure $next)
    {
        $outletId = $request->header('x-outlet-id');

        $outlet = $request->user()->outlets()->find($outletId);

        if (!$outlet) {
            return response()->json(['message' => 'Outlet access revoked'], 403);
        }

        return $next($request);
    }
}
