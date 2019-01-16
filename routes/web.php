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

Route::get('/codemirror', function () {
    return view('codemirror');
});

Route::get('/vuetify/data-table', function () {
    return view('vuetify/data-table');
});

Route::get('factory/vuetify/data-table', 'FactoryDataTable@index');
Route::get('factory/vuetify/data-table-crud', 'FactoryDataTableCrud@index');

Route::get('/vuetify/data-table-order-by', function () {
    return view('vuetify/data-table-order-by');
});

Route::get('/vuetify/data-table-order-by-crud', function () {
    return view('vuetify/data-table-order-by-crud');
});

Route::get('/api/v1','EloquentJsApi@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');