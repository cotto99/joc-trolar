<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BodegaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\FleteController;
use App\Http\Controllers\CandadoController;
use App\Http\Controllers\GastoFijoController;
use App\Http\Controllers\GastoVariableController;
use App\Http\Controllers\VentaCandadoController;
use App\Http\Controllers\DashboardAdminController;

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

    Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])
    ->name('dashboard.admin');
// Bitácoras
Route::resource('bitacoras', BitacoraController::class);

// Fletes
Route::resource('fletes', FleteController::class);

// Candados
Route::resource('candados', CandadoController::class);
Route::post('/candados/vender', [CandadoController::class, 'vender'])->name('candados.vender');

// Gastos Fijos
Route::resource('gastos-fijos', GastoFijoController::class);
Route::patch('/gastos-fijos/{gastoFijo}/desactivar', [GastoFijoController::class, 'desactivar'])->name('gastos-fijos.desactivar');
Route::patch('/gastos-fijos/{gastoFijo}/reactivar', [GastoFijoController::class, 'reactivar'])->name('gastos-fijos.reactivar');

// Gastos Variables
Route::resource('gastos-variables', GastoVariableController::class);

Route::resource('ventas-candados', VentaCandadoController::class)->only(['index', 'create', 'store', 'show', 'destroy']);



    Route::resource('pagos', PagoController::class)->only(['index']);
    Route::patch('/pagos/{pago}/pagar', [PagoController::class, 'marcarPagado'])->name('pagos.pagar');
});

require __DIR__.'/auth.php';