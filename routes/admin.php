<?php

use App\Http\Controllers\Admin\CursController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\ProgramController;
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

Route::controller(OrganizationController::class)->group(function(){
    Route::post("/org/list","list")->middleware(AsyncAdminMiddleware::class)->name("org.list");
    Route::post("/org/item","item")->middleware(AsyncAdminMiddleware::class)->name("org.item");
    Route::post("/org/create","create")->middleware(AsyncAdminMiddleware::class)->name("org.create");
    Route::post("/org/update","update")->middleware(AsyncAdminMiddleware::class)->name("org.update");
    Route::post("/org/delete","delete")->middleware(AsyncAdminMiddleware::class)->name("org.delete");
});

Route::controller(ProgramController::class)->group(function(){
    Route::post("/program/list",'list')->middleware(AsyncAdminMiddleware::class)->name('program.list');
    Route::post("/program/item",'item')->middleware(AsyncAdminMiddleware::class)->name('program.item');
    Route::post("/program/create",'create')->middleware(AsyncAdminMiddleware::class)->name('program.create');
    Route::post("/program/update",'update')->middleware(AsyncAdminMiddleware::class)->name('program.update');
    Route::post("/program/delete",'delete')->middleware(AsyncAdminMiddleware::class)->name('program.delete');
});

Route::controller(CursController::class)->group(function(){
    Route::post("/curs/list",'list')->middleware(AsyncAdminMiddleware::class)->name('curs.list');
    Route::post("/curs/item",'item')->middleware(AsyncAdminMiddleware::class)->name('curs.item');
    Route::post("/curs/create",'create')->middleware(AsyncAdminMiddleware::class)->name('curs.create');
    Route::post("/curs/update",'update')->middleware(AsyncAdminMiddleware::class)->name('curs.update');
    Route::post("/curs/delete",'delete')->middleware(AsyncAdminMiddleware::class)->name('curs.delete');
});