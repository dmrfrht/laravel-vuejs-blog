<?php

use Illuminate\Support\Facades\Route;

Route::post('app/create_tag', 'AdminController@addTag');
Route::get('app/get_tags', 'AdminController@getTags');

Route::get('/', function () {
  return view('welcome');
});

Route::any('{slug}', function () {
  return view('welcome');
});
