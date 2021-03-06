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

Route::get('/qr', function () {
    return view('qr');
});
 
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// *********

Route::get('/categorias', 'CategoriaController@index');

Route::put('/categorias/actualizar', 'CategoriaController@update');

Route::post('/categorias/guardar', 'CategoriaController@store');

Route::delete('/categorias/borrar/{id}', 'CategoriaController@destroy');

Route::get('/categorias/buscar', 'CategoriaController@show');

// *********

Route::get('/productos', 'ProductoController@index');

Route::post('/productos/actualizar', 'ProductoController@update');

Route::post('/productos/guardar', 'ProductoController@store');

Route::delete('/productos/borrar/{id}', 'ProductoController@destroy');

Route::get('/productos/buscar', 'ProductoController@show');

// ****

Route::get('local/{local}/mesa/{mesa}', [
'as' => 'verMenu', 'uses' => 'MesaController@verMenu']);

// *****
