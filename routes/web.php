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
// Route::get('/', 'PostController@index')->name('post.index');
Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'PostController@index')->name('post.index');
    Route::post('/post/store', 'PostController@store')->name('post.store');
    Route::get('/post/{post}/edit', 'PostController@edit')->name('post.edit');
    Route::put('/post/{id}/update', 'PostController@update')->name('post.update');
    Route::delete('/post/{id}', 'PostController@destroy')->name('post.destroy');

    Route::post('/post/{id}/like', 'PostController@like')
        ->name('post.like');
    Route::post('/post/{id}/dislike', 'PostController@dislike')
        ->name('post.dislike');
    
    Route::post('/comment/store', 'CommentController@store')->name('comment.store');

});
