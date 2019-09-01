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
    return view('home');
});
Route::get('view-data', function () {
    return view('view-data');
});

Route::get('save-person', 'Api\PersonController@savePerson');
Route::get('get-person-films', 'Api\PersonController@getFilms');
Route::get('save-film', 'Api\FilmController@saveFilm');
Route::get('get-film-characters', 'Api\FilmController@getCharacters');