<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/antrian-display', function () {
    return view('displayAntrian');
});

Route::get('/generate-antrian', [AntrianController::class, 'generateAntrian']);
Route::get('/get-antrian', [AntrianController::class, 'getAntrian']);
Route::get('/get-nomor-antrian', [AntrianController::class, 'getNomorAntrian']);
Route::get('/pendaftaran/{tanggal?}', [AntrianController::class, 'getDataAntrian'])->name('pendaftaran');

Route::middleware(['auth'])->group(function () {
    Route::get('/home/{tanggal?}', [HomeController::class, 'index'])->name('home');
    Route::post('/panggil-antrian/{noId}/{noAntrian}', [AntrianController::class, 'panggilAntrian']);
    Route::get('/get-antrian-selanjutnya', [AntrianController::class, 'getNomorAntrianSelanjutnya']);
});

Auth::routes([
    'register' => false, // Registration Routes...
]);


