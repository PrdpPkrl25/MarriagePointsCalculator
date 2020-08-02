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


Route::get('/', function () {return view('welcome');});

Route::resource('games', 'GameController');
Route::resource('players', 'PlayerController');
Route::resource('points', 'PointController');
Route::get('total-points', 'PointController@total')->name('total.points');
Route::get('total-points/{game_id}', 'PointController@showTotal')->name('show.points');
Route::post('points/delete/{roundId}', 'PointController@destroy')->name('points.delete');
