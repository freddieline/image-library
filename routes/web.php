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

//Route::get('/', function () {
//    return view('welcome');
//});

// SSG
Route::get( '/', function () {
    return view('html.template');
});

// Route POST request to Login Handler
Route::post( '/auth/login', 'Auth\LoginController@login' );
Route::get( '/auth/logout', 'Auth\LoginController@logout' );

Route::get( '/meal/{id}', 'MealsController@getMeal' );

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
