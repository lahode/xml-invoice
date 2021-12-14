<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\XMLController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('read-xml',[XMLController::class, 'readXML']);
Route::get('create-xml',[XMLController::class, 'createXML']);
Route::get('print-pdf',[XMLController::class, 'printPDF']);
