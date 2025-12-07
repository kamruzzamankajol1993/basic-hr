<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::post('/register/check-email', [RegisterController::class, 'checkEmail'])->name('register.check.email');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// HRM Module Routes (Protected)
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
   
    // Department Routes
    Route::resource('departments', DepartmentController::class);
    Route::get('departments-data', [DepartmentController::class, 'data'])->name('departments.data');
    //End Department Routes

    // Skill Routes 
    Route::resource('skills', SkillController::class);
    Route::get('skills-data', [SkillController::class, 'data'])->name('skills.data');
    // End Skill Routes

    // Employee Routes 
    Route::resource('employees', EmployeeController::class);
    Route::get('employees-data', [EmployeeController::class, 'data'])->name('employees.data');
    // Additional jQuery Interaction Route: AJAX Email Check
    Route::post('employees-check-email', [EmployeeController::class, 'checkEmail'])->name('employees.check.email');
    // End Employee Routes
});
