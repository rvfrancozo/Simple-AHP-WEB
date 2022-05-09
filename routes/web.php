<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObjetivoController;
use App\Http\Controllers\AHPController;
use App\Http\Controllers\NodesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HumanReportController;
use App\Http\Controllers\NumericalReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Testes;
use App\Http\Controllers\viewusers;

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

Route::get('/auth/github/redirect', [AuthController::class, 'githubredirect'])->name('githublogin');
Route::get('/auth/github/callback', [AuthController::class, 'githubcallback']);

Route::get('/auth/google/redirect', [AuthController::class, 'googleredirect'])->name('googlelogin');
Route::get('/auth/google/callback', [AuthController::class, 'googlecallback']);

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/AHP', [AHPController::class, 'AHP']);

Route::get('/nodes', [NodesController::class, 'index']);
Route::get('/error', [NodesController::class, 'error']);
//Route::get('/home', [NodesController::class, 'index']);
Route::get('/home', function () { return redirect('/'); });
Route::get('/nodes/{id}/criteria', [NodesController::class, 'criteria']);
Route::get('/nodes/{id}/alternatives', [NodesController::class, 'alternatives']);
Route::get('/comparisons/{up}/{id}', [NodesController::class, 'comparisons']);
Route::post('/formCreateNode/{up}', [NodesController::class, 'formCreateNode']);
Route::post('/createNode/{up}', [NodesController::class, 'createNode']);
Route::post('/UpdateScore/{proxy}', [NodesController::class, 'UpdateScore']);
Route::get('/node/{id}/remove', [NodesController::class, 'removeNode']);
Route::get('/nodes/{id}/report', [ReportController::class, 'report']);
Route::get('/nodes/{id}/NumericalReport', [NumericalReportController::class, 'report']);
Route::get('/nodes/{id}/HumanReport', [HumanReportController::class, 'report']);


Route::get('/allusers', [viewusers::class, 'view']);

Route::get('/testes/{id}', [Testes::class, 'testes']);
