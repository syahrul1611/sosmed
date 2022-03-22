<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;

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

Route::controller(ViewController::class)->group(function()
{
    // home view
    Route::get('/','index')->middleware('auth')->name('home');
    // explore view
    Route::get('/explore','explore')->middleware('auth');
    // chat list view
    Route::get('/chatlist','list')->middleware('auth');
    // chat view
    Route::get('/chat-{user:username}','chat')->middleware('auth');
    // dashboard view
    Route::get('/dashboard-{user:username}','dashboard')->middleware('auth');
});

Route::controller(UserController::class)->group(function()
{
    // add friend route
    Route::post('/addfriend','addFriend')->middleware('auth')->name('addFriend');
    // acc friend request route
    Route::post('/acc','friendAcc')->middleware('auth')->name('acc');
    // rjc friend request route
    Route::post('/rjc','friendRjc')->middleware('auth')->name('rjc');
    // edit profile
    Route::put('/edit','update')->middleware('auth')->name('edit');
});

Route::controller(PostController::class)->group(function()
{
    // add post route
    Route::post('/addpost','store')->middleware('auth')->name('post');
    // query post route
    Route::put('/editPost','edit')->middleware('auth')->name('editPost');
    // delete post route
    Route::post('/delete','delete')->middleware('auth')->name('delete');
    // comment route
    Route::post('/comment','comment')->middleware('auth')->name('comment');
    // like route
    Route::post('/like','like')->middleware('auth')->name('like');
});

Route::controller(ChatController::class)->group(function()
{
    // send chat
    Route::post('/send','send')->middleware('auth')->name('send');
    // get chat
    Route::post('/get','get')->middleware('auth')->name('get');
});

Auth::routes();
