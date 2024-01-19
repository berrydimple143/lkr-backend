<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\RankController;
use App\Http\Controllers\Api\CrewController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AreaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('list', 'index');    
    Route::post('show', 'show');
    Route::post('create', 'create');
    Route::post('edit', 'edit');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});

Route::controller(ClientController::class)->prefix('clients')->group(function () {
    Route::get('list', 'index'); 
    Route::post('show', 'show');
    Route::post('info', 'info');
    Route::post('create', 'create');
    Route::post('payment', 'payment');
    Route::post('payments', 'payments');
    Route::post('payment/destroy', 'destroyPayment');
    Route::post('edit', 'edit');
    Route::post('printSOA', 'printSOA');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});

Route::controller(AreaController::class)->prefix('areas')->group(function () {
    Route::get('list', 'index'); 
    Route::post('show', 'show');
    Route::post('create', 'create');
    Route::post('edit', 'edit');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});
