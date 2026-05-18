<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CsvDownloadController;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ContactController::class, 'index'])->name("rewrite");

Route::post('/confirm', [ContactController::class, 'confirm']);

Route::post('/thanks', [ContactController::class, 'store']);

Route::get('/register', [AuthController::class, 'registerView']);

Route::post('/register', [AuthController::class, 'register']);

/* ログインしていない状態で/adminにアクセスしてもログイン画面を表示するよう「->name('login') 」をつける */
Route::get('/login', [AuthController::class, 'loginView'])->name('login') ;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminContactController::class, 'admin']);

    Route::get('/search', [AdminContactController::class, 'search']);

    Route::get('/reset', [AdminContactController::class, 'reset']);

    Route::post('/delete', [AdminContactController::class, 'delete']);

    Route::get('/export', [AdminContactController::class, 'export']);

});