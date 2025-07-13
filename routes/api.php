<?php

use App\Http\Controllers\VisaDossierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('documents')->group(function () {
    Route::get('/list', [VisaDossierController::class, 'listDocuments']);
    Route::post('/upload', [VisaDossierController::class, 'uploadDocuments']);
    Route::delete('/delete', [VisaDossierController::class, 'deleteDocument']);
});
