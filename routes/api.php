<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->get('/ping', function () {
    return response()->json(['ok' => true]);
});
