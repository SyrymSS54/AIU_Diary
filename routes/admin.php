<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserSController;
use App\Http\Middleware\AsyncAdminMiddleware;

Route::controller(UserSController::class)->group(function(){
    Route::post("/users/list","list")->middleware(AsyncAdminMiddleware::class)->name('users.list');
    Route::post("/users/item","item")->middleware(AsyncAdminMiddleware::class)->name('users.item');
    Route::post("/users/create","create")->middleware(AsyncAdminMiddleware::class)->name('users.create');
    Route::post("/users/update","update")->middleware(AsyncAdminMiddleware::class)->name('users.update');
    Route::post("/users/delete","delete")->middleware(AsyncAdminMiddleware::class)->name('users.delete');
});