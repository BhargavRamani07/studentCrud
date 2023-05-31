<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);

    // Student Routes
    Route::get('/student', [App\Http\Controllers\admin\Student::class, 'report']);
    Route::post('/getStudents', [App\Http\Controllers\admin\Student::class, 'getStudents']);
    Route::post('/addStudent', [App\Http\Controllers\admin\Student::class, 'store']);

    Route::post('/getStudent', [App\Http\Controllers\admin\Student::class, 'getSingleStudent']);
    Route::post('/updateStudent', [App\Http\Controllers\admin\Student::class, 'updateStudent']);

    Route::post('/deleteStudent', [App\Http\Controllers\admin\Student::class, 'deleteStudent']);
});
