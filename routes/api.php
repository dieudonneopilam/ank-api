<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('', function () {
        return response('bienvenue sur ank-api', 200);
    });
    // Url Authentification
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('signup', 'App\Http\Controllers\AuthController@signup');
    //Url of Post
    Route::get('posts', 'App\Http\Controllers\PostController@index');
    Route::get('posts/{id}', 'App\Http\Controllers\PostController@show');
    // Url of User
    Route::get('users', 'App\Http\Controllers\UserController@index');
    Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
    // Url of Comment
    Route::get('comments', 'App\Http\Controllers\CommentController@index');
    Route::get('comments/{id}', 'App\Http\Controllers\CommentController@show');
    // Url middleware authentification
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'App\Http\Controllers\AuthController@logout');
        // Url od Post
        Route::post('posts', 'App\Http\Controllers\PostController@store');
        Route::put('posts/{id}', 'App\Http\Controllers\PostController@update');
        Route::delete('posts/{id}', 'App\Http\Controllers\PostController@destroy');
        // Url of User
        Route::post('users', 'App\Http\Controllers\UserController@store');
        Route::put('users/{id}', 'App\Http\Controllers\UserController@update');
        Route::delete('users/{id}', 'App\Http\Controllers\UserController@destroy');
        // Url of Comment
        Route::post('comments', 'App\Http\Controllers\CommentController@store');
        Route::put('comments/{id}', 'App\Http\Controllers\CommentController@update');
        Route::delete('comments/{id}', 'App\Http\Controllers\CommentController@destroy');
    });
});
