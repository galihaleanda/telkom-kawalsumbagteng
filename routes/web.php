<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\GoogleAuthController;

use App\Http\Controllers\MasterData\WitelController;
use App\Http\Controllers\MasterData\BranchController;
use App\Http\Controllers\MasterData\DatelController;
use App\Http\Controllers\MasterData\ServiceAreaController;
use App\Http\Controllers\MasterData\SektorController;
use App\Http\Controllers\MasterData\StoController;
use App\Http\Controllers\MasterData\PicController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::prefix('master-data')->group(function () {
    Route::resource('witels', WitelController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('datels', DatelController::class);
    Route::resource('service-areas', ServiceAreaController::class);
    Route::resource('sektors', SektorController::class);
    Route::resource('stos', StoController::class);
    Route::resource('pics', PicController::class);
});