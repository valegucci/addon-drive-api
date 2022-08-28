<?php

use Illuminate\Support\Facades\Route;
use Vale\Addons\Drive\Http\Controllers\ApiController;
use Vale\Addons\Drive\Http\Middleware\DriveApiAuth;

Route::middleware([DriveApiAuth::class])->group(function () {
    Route::post('/move-file', [ApiController::class, 'moveToFolder']);
});