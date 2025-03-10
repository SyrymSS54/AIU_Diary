<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIU\AccountController;
use App\Http\Middleware\AdminFilter;
use App\Http\Middleware\StudentFilter;
use App\Http\Middleware\TeacherFilter;

Route::controller(AccountController::class)->group(function(){
    Route::get("/","index")->name('login');
    Route::post("/signin",'signin');

    Route::get("/admin/{any}","admin_page")->name('admin')->middleware(AdminFilter::class);
    Route::get("/teacher/{any}","teacher_page")->name('teacher')->middleware(TeacherFilter::class);
    Route::get("/student/{any}",'student_page')->name('student')->middleware(StudentFilter::class);
});