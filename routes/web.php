<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/upload-file', function () {
    return view('upload'); // Замените 'upload' на имя вашего файла представления, например 'upload.blade.php'
});
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
