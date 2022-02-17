<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/test', function () {
//    event(new \App\Events\MyEvent('hello world'));
    event(new App\Events\StatusLiked('Someone'));
    return "Event has been sent!";
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('send', 'HomeController@sendNotification');
Route::get('repo', 'HomeController@repo');
//mb_substr(\Str::random(30), 0, 6)
