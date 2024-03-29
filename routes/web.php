<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractCycleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//app routes start

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/customers');
    });
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customer/create', [CustomerController::class, 'create']);
    Route::post('/customer/store', [CustomerController::class, 'store']);
    Route::get('/customer/edit/{customer}', [CustomerController::class, 'edit']);
    Route::put('/customer/update/{customer}', [CustomerController::class, 'update']);
    Route::delete('/del/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::get('/contract/create', [ContractController::class, 'create']);
    Route::get('/contract/edit/{contract}', [ContractController::class, 'edit']);
    Route::put('/contract/update/{contract}', [ContractController::class, 'update']);
    Route::post('/contract/store', [ContractController::class, 'store']);

    Route::get('/cycle/create/{contract_id}', [ContractCycleController::class, 'create'])->name('cycles.create');
    Route::get('/cycle/edit/{contractCycle}', [ContractCycleController::class, 'edit'])->name('cycles.edit');

    Route::post('/cycle/store', [ContractCycleController::class, 'store']);
    Route::put('/cycle/update/{contractCycle}', [ContractCycleController::class, 'update']);

    Route::get('/contracts', [ContractController::class, 'index'])->name('contract_idx');

    Route::post('/customer/export', [CustomerController::class, 'exporter'])->name('customers.export');
    Route::post('/contract/export', [ContractController::class, 'exporter'])->name('contracts.export');

});
