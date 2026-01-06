<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\FontSizeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WochenaufgabenController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ThemenController;
use App\Http\Controllers\ApiKeyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ThemeController;


Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/search', [SearchController::class,
    'search'])->name('search');

Route::get('/impressum', function () {
    return view('impressum');
});

Route::get('/kontakt', function () {
    return view('kontakt');
});

Route::get('/datenschutz', function () {
    return view('datenschutz');
});


Route::get('/login', function () {
    return view('login');//
});

Route::get('/registertest', function () {
    return view('registertest');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::post('/font-size/update', [FontSizeController::class, 'update'])
    ->name('font-size.update');


Route::get('/fachauswahl', function () {

    if (!Auth::check()) {
        return redirect('/login')->with('status', 'Darauf darfst du nicht zugreifen. Bitte melde dich an.');
    }
    return view('fachauswahl');
});


Route::get('/wochenaufgaben', [WochenaufgabenController::class, 'index'])
    ->middleware('auth')
    ->name('wochenaufgaben');

Route::get('/settings', function () {

    if (!Auth::check()) {
        return redirect('/login')->with('status', 'Darauf darfst du nicht zugreifen. Bitte melde dich an.');
    }
    return view('settings');
})->name('settings');

Route::get('/themen', [ThemenController::class, 'index'])
    ->middleware('auth')
    ->name('themen');

Route::get('/baukasten', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name('baukasten');

// Aufgaben-Serialisierung für den Baukasten
Route::post('/baukasten/serialize', [TaskController::class, 'serializeMathTask'])
    ->middleware('auth')
    ->name('task.serialize');

// PDF Upload
Route::get('/pdf-upload', [TaskController::class, 'pdfUpload'])
    ->middleware('auth')
    ->name('pdf.upload');

Route::post('/pdf-upload', [TaskController::class, 'storePdf'])
    ->middleware('auth')
    ->name('pdf.store');

Route::post('/font-size/update', [FontSizeController::class, 'update'])
    ->name('font-size.update');



Route::get('/code', [CodeController::class, 'create'])->name('code');
Route::post('/code/save', [CodeController::class, 'store'])->name('code.store');
Route::post('/code/destroy/{id}', [CodeController::class, 'destroy'])->name('code.destroy');
Route::get('/settings', [CodeController::class, 'show'])->name('settings');

Route::post('/parent/link-child', [CodeController::class, 'linkChild'])
    ->middleware('auth')
    ->name('parent.link-child');

Route::delete('/parent/unlink-child/{studentId}', [CodeController::class, 'unlinkChild'])
    ->middleware('auth')
    ->name('parent.unlink-child');

Route::post('/category/store', [CategoryController::class, 'store'])
    ->middleware('auth')
    ->name('category.store');

Route::put('/category/{categoryName}', [CategoryController::class, 'update'])
    ->middleware('auth')
    ->name('category.update');

Route::delete('/category/{categoryName}', [CategoryController::class, 'destroy'])
    ->middleware('auth')
    ->name('category.destroy');

// Verwaltungsseite für Aufgaben und Kategorien
Route::get('/verwaltung', [TaskController::class, 'index'])
    ->middleware('auth')
    ->name('verwaltung');

Route::delete('/task/{id}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('task.destroy');

Route::post('/task/complete', [TaskController::class, 'markTaskCompleted'])
    ->middleware('auth')
    ->name('task.complete');

Route::post('/settings/llm-api-key', [ApiKeyController::class, 'updateLlmApiKey'])
    ->middleware('auth')
    ->name('settings.llm-api-key');

Route::post('/api/check-answer', [ApiKeyController::class, 'checkAnswerWithLlm'])
    ->middleware('auth')
    ->name('api.check-answer');

Route::post('/theme/change', [ThemeController::class, 'change'])->name('theme.change');
