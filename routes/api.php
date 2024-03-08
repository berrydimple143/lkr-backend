<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\CommonController;

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

Route::controller(AgentController::class)->prefix('agents')->group(function () {
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
    Route::post('getCollectablesByArea', 'getCollectablesByArea');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});

Route::controller(ExpensesController::class)->prefix('expenses')->group(function () {
    Route::get('list', 'index'); 
    Route::post('show', 'show');
    Route::post('create', 'create');
    Route::post('edit', 'edit');
    Route::post('byDate', 'byDate');
    Route::post('reports', 'reports');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});

Route::controller(ReportsController::class)->prefix('reports')->group(function () {
    Route::get('list', 'index'); 
    Route::post('show', 'show');
    Route::post('create', 'create');
    Route::post('edit', 'edit');
    Route::post('byDate', 'byDate');
    Route::post('reports', 'reports');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
});

Route::controller(ManagerController::class)->prefix('managers')->group(function () {
    Route::get('list', 'index');
    Route::post('info', 'info');
    Route::post('create', 'create');
    Route::post('add/agent', 'addAgent');
    Route::post('agents', 'getAgents');
    Route::post('edit', 'edit');
    Route::post('update', 'update');
    Route::post('destroy', 'destroy');
    Route::post('agent/destroy', 'deleteAgent');
});

Route::controller(CommonController::class)->prefix('common')->group(function () {    
    Route::post('monthly-expenses', 'monthlyExpense');    
});
