<?php

use App\Http\Controllers\Admin\ConceptQualityValidationController as AdminConceptQualityValidationController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConceptQualityValidationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SiacController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiacController::class, 'home'])->name('siac.home');
Route::get('/buscar', [SiacController::class, 'search'])->name('siac.search');
Route::get('/documentos', [DocumentController::class, 'index'])->name('siac.documents.index');
Route::get('/documentos/archivo/{documento}', [DocumentController::class, 'download'])
    ->name('siac.documents.download');
Route::get('/pagina/{slug}', [SiacController::class, 'page'])->name('siac.page');

Route::post('/validacion-concepto-calidad', [ConceptQualityValidationController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('siac.concept_validation.store');
Route::get('/validacion-concepto-calidad/resumen', [ConceptQualityValidationController::class, 'summary'])
    ->name('siac.concept_validation.summary');
Route::get('/validacion-concepto-calidad/resumen-acta', [ConceptQualityValidationController::class, 'actaSummary'])
    ->name('siac.concept_validation.acta');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::resource('documentos', AdminDocumentController::class)
            ->except(['show'])
            ->names('documents');

        Route::get('validaciones-concepto/exportar', [AdminConceptQualityValidationController::class, 'export'])
            ->name('concept_validations.export');
        Route::post('validaciones-concepto/importar', [AdminConceptQualityValidationController::class, 'import'])
            ->name('concept_validations.import');
        Route::delete('validaciones-concepto/todas', [AdminConceptQualityValidationController::class, 'destroyAll'])
            ->name('concept_validations.destroy_all');
        Route::resource('validaciones-concepto', AdminConceptQualityValidationController::class)
            ->except(['show'])
            ->names('concept_validations')
            ->parameters(['validaciones-concepto' => 'validacion']);
    });
