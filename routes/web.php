<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MahasiswaController;
use Barryvdh\DomPDF\Facade\Pdf;

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

Route::get('/', function () {
    return view('welcome');
});

//Menampilkan product dan CRUD
Route::resource('/product', ProductController::class);

Route::get('mahasiswa/pdf', [MahasiswaController::class, 'cetak_pdf']);
Route::resource('mahasiswa', MahasiswaController::class);
