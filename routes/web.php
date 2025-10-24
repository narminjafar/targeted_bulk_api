<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use \App\Http\Controllers\Api\V1\UnsubscribeController;

Route::get('/', function () {
    return view('welcome');
});
    
Route::get('/api/documentation', function () {
    return redirect('/api/docs');
});

