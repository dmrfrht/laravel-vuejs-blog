<?php

use Illuminate\Support\Facades\Route;

Route::post('app/create_tag', 'AdminController@addTag');
Route::get('app/get_tags', 'AdminController@getTags');
Route::post('app/edit_tag', 'AdminController@editTag');
Route::post('app/delete_tag', 'AdminController@deleteTag');
Route::post('app/upload', 'AdminController@upload');

Route::get('/', function () {
  return view('welcome');
});

Route::any('{slug}', function () {
  return view('welcome');
});
