<?php

use App\Http\Controllers\Key\KeyIndexController;
use App\Http\Controllers\Key\KeyStoreController;
use App\Http\Controllers\Key\KeyUpdateController;
use App\Http\Controllers\Key\KeyDestroyController;

use App\Http\Controllers\KeyMovement\KeyCheckoutController;
use App\Http\Controllers\KeyMovement\KeyReturnController;
use App\Http\Controllers\KeyPerson\KeyPersonDestroyController;
use App\Http\Controllers\KeyPerson\KeyPersonIndexController;
use App\Http\Controllers\KeyPerson\KeyPersonStoreController;
use App\Http\Controllers\KeyPerson\KeyPersonUpdateController;
use App\Http\Controllers\KeyReservation\KeyReservationStoreController;
use App\Http\Controllers\KeyReservation\KeyReservationUpdateController;
use App\Http\Controllers\KeyReservation\KeyReservationDestroyController;
use App\Http\Controllers\KeyResponsible\KeyResponsibleDestroyController;
use App\Http\Controllers\KeyResponsible\KeyResponsibleIndexController;
use App\Http\Controllers\KeyResponsible\KeyResponsibleStoreController;
use App\Http\Controllers\KeyResponsible\KeyResponsibleUpdateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    
    Route::get('/keys', KeyIndexController::class)->name('keys.index');
    Route::post('/keys', KeyStoreController::class)->name('keys.store');
    Route::put('/keys/{key}', KeyUpdateController::class)->name('keys.update');
    Route::delete('/keys/{key}', KeyDestroyController::class)->name('keys.destroy');

    Route::post('/keys/{key}/checkout', KeyCheckoutController::class)->name('keys.checkout');
    Route::post('/keys/{key}/return', KeyReturnController::class)->name('keys.return');

    Route::post('/reservations', KeyReservationStoreController::class)->name('reservations.store');
    Route::put('/reservations/{keyReservation}', KeyReservationUpdateController::class)->name('reservations.update');
    Route::delete('/reservations/{keyReservation}', KeyReservationDestroyController::class)->name('reservations.destroy');

    Route::get('/people', KeyPersonIndexController::class)->name('people.index');
    Route::post('/people', KeyPersonStoreController::class)->name('people.store');
    Route::put('/people/{keyPerson}', KeyPersonUpdateController::class)->name('people.update');
    Route::delete('/people/{keyPerson}', KeyPersonDestroyController::class)->name('people.destroy');

    Route::get('/responsibles', KeyResponsibleIndexController::class)->name('responsibles.index');
    Route::post('/responsibles', KeyResponsibleStoreController::class)->name('responsibles.store');
    Route::put('/responsibles/{keyResponsible}', KeyResponsibleUpdateController::class)->name('responsibles.update');
    Route::delete('/responsibles/{keyResponsible}', KeyResponsibleDestroyController::class)->name('responsibles.destroy');
});
