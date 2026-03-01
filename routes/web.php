<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BodegaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\PagoController;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bodegas', BodegaController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('clientes', ClienteController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('contratos', ContratoController::class)
        ->only(['index', 'store']);
    Route::patch('/contratos/{contrato}/cancelar', [ContratoController::class, 'cancelar'])->name('contratos.cancelar');
    Route::get('/contratos/{contrato}/descargar', [ContratoController::class, 'descargar'])->name('contratos.descargar');

    Route::resource('pagos', PagoController::class)->only(['index']);
    Route::patch('/pagos/{pago}/pagar', [PagoController::class, 'marcarPagado'])->name('pagos.pagar');
});

require __DIR__.'/auth.php';