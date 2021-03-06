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

// Home
Route::get('/', function(){
    return redirect('/heroes');
})->name('home');

// Heroes
Route::get('/heroes', 'HeroController@index')->name('heroes');
Route::get('/heroes/{id}', 'HeroController@show');
Route::get('/heroes/{id}/enemies', 'HeroController@enemies');
Route::get('/heroes/{id}/allies', 'HeroController@allies');

// Players
Route::get('/players', function () {
    return view('players.index');
})->name('players');
Route::get('/players/search', function(){
    return redirect('/players');
})->name('search');
Route::post('/players/search', 'PlayerController@search');
Route::get('/players/{id}', 'PlayerController@show');

// Maps
Route::get('/maps', 'MapController@index')->name('maps');
Route::get('/maps/{id}', 'MapController@show');


Route::resource('map', 'MapController');
Route::resource('type', 'TypeController');
Route::resource('game', 'GameController');
Route::resource('version', 'VersionController');
Route::resource('player', 'PlayerController');
Route::resource('hero', 'HeroController');
Route::resource('talent', 'TalentController');
Route::resource('participation', 'ParticipationController');
Route::resource('participation_talent', 'Participation_talentController');
Route::resource('role', 'RoleController');
