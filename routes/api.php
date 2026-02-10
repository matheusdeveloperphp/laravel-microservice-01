<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return response()->json(['message' => 'sucess '], 200);
});


Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class);

Route::apiResource('companies', App\Http\Controllers\Api\CompanyController::class);
