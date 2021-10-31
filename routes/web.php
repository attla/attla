<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('welcome')->get('/', function () {
    return view('welcome');
});
