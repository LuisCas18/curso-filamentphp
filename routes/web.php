<?php

use App\Http\Controllers\PDFController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/developments');
});

Route::get('/pruebas/generate/timesheet/{user}', [PDFController::class, 'TimesheetRecords'])->name('pdf.example');
