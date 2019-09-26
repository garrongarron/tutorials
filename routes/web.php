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
$whitelist = array(
    '127.0.0.1',
    '172.18.0.1',
    '::1'
);
$local = false;
if(isset($_SERVER['REMOTE_ADDR'])){
    $local = true;
}
define("PEPE", $local);


Route::get('/', function () {
    return view('porfolio');
});


    Route::group(['prefix'=>'/tutorial','as'=>'porfolio.'], function(){

        Route::get('/', function () {
            return view('porfolio');
        })->name('home');
        Route::get('restful-api-react', function () {
            return view('porfolio.react');
        })->name('react');
        Route::get('restful-api-angular', function () {
            return view('porfolio.angular');
        })->name('angular');
        Route::get('restful-api-vue', function () {
            return view('porfolio.vue');
        })->name('vue');



        Route::get('restful-api-php', function () {
            return view('porfolio.php');
        })->name('php');
        Route::get('restful-api-node', function () {
            return view('porfolio.node');
        })->name('node');
        Route::get('restful-api-python', function () {
            return view('porfolio.python');
        })->name('python');
    });
