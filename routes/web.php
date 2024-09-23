<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/init', [Controller::class, 'init']);
Route::get('/character',[Controller::class, 'getFirstCharacter']);
Route::get('/characters/{id}', [Controller::class, 'getCharacterById']);
Route::get('/characters/{id}/export', [Controller::class, 'exportCharacterByIdToExcel']);
Route::get('/',function(){
    return view('home');
});

