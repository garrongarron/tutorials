<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/resume', function () {
    return view('welcome');
});
Route::get('/porfolio', function () {
    return view('porfolio');
});

Route::group(['prefix'=>'porfolio','as'=>'porfolio.'], function(){
    Route::get('/', function () {
        return view('porfolio');
    });
    Route::get('react', function () {
        return view('porfolio.react');
    })->name('react');
    Route::get('angular', function () {
        return view('porfolio.angular');
    })->name('angular');
    Route::get('vue', function () {
        return view('porfolio.vue');
    })->name('vue');



    Route::get('php', function () {
        return view('porfolio.php');
    })->name('php');
    Route::get('node', function () {
        return view('porfolio.node');
    })->name('node');
    Route::get('python', function () {
        return view('porfolio.python');
    })->name('python');
});
