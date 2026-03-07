<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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

Route::get('/',[ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'search']);
// Route::get('/?tab=mylist',[ItemController::class, 'mylist']);
Route::get('/login',[LoginController::class, 'login']);
Route::get('/item/{item_id}', [ItemController::class, 'getDetail']);
Route::post('/comment/{item_id}', [ItemController::class, 'createComment']);
Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address']);
Route::post('/address', [PurchaseController::class, 'createAddress']);
// Route::get('/sell',[ItemController::class, 'sell']);
// Route::get('/mypage',[UserController::class, 'mypage']);
Route::get('/mypage/profile',[UserController::class, 'edit']);
Route::post('/profile/update', [PurchaseController::class, 'updateProfile']);
// Route::get('/mypage?page=buy',[UserController::class, 'showGet']);
// Route::get('/mypage?page=sell',[UserController::class, 'showSell']);

// いいね機能
Route::get('/item/like/{id}',  [ItemController::class, 'like'])->name('item.like');
Route::get('/item/unlike/{id}',  [ItemController::class, 'unlike'])->name('item.unlike');