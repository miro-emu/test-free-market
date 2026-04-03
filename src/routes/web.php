<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MailSendController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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
Route::get('/login',[LoginController::class, 'login']);

Route::get('/item/{item_id}', [ItemController::class, 'getDetail']);
Route::post('/comment/{item_id}', [ItemController::class, 'createComment']);

Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address']);
Route::post('/purchase/address/{item_id}', [PurchaseController::class,'createAddress']);
Route::get('/purchase/{item_id}', [PurchaseController::class, 'confirm']);
Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success']);
Route::post('/stripe/webhook', [PurchaseController::class, 'webhook']);

Route::get('/sell',[ItemController::class, 'sell']);
Route::post('/sell',[ItemController::class, 'createSell']);

Route::get('/mypage',[UserController::class, 'mypage']);
Route::get('/mypage/profile',[UserController::class, 'edit'])->middleware(['auth', 'verified']);
Route::post('/profile/update', [UserController::class, 'updateProfile']);

// いいね機能
Route::get('/item/like/{id}',  [ItemController::class, 'like'])->name('item.like');
Route::get('/item/unlike/{id}',  [ItemController::class, 'unlike'])->name('item.unlike');

// メール認証画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// メール認証
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
// 認証メール再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');