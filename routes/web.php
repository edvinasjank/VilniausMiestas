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

Route::get('/', 'AdminController@index');
Route::post('/delete-people.html', 'AdminController@deletePeople');
Route::get('/auto', 'AdminController@autocomplete');
Route::post('/edit.html', 'AdminController@edit');
Route::post('/import.html', 'AdminController@import');