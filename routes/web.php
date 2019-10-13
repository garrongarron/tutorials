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

        Route::get('framework-symfony-4', function () {
            return view('porfolio.framework-symfony-4');
        })->name('framework-symfony-4');

        Route::get('php-and-mysql', function () {
            return view('porfolio.php-and-mysql');
        })->name('php-and-mysql');

        Route::get('restful-api-symfony-4', function () {
            return view('porfolio.restful-api-symfony-4');
        })->name('restful-api-symfony-4');
        
        Route::get('symfony-sonata-admin', function () {
            return view('porfolio.symfony-sonata-admin');
        })->name('symfony-sonata-admin');

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


        Route::get('docker', function () {
            return view('porfolio.docker');
        })->name('docker');


        Route::get('ssh-key', function () {
            return view('porfolio.ssh-key');
        })->name('ssh-key');

        Route::get('bash', function () {
            return view('porfolio.bash');
        })->name('bash');




        Route::get('whyhireme', function () {
            return view('porfolio.whyhireme');
        })->name('whyhireme');


        Route::get('chat', function () {
            return view('chat.chat');
        })->name('chat');

    });
