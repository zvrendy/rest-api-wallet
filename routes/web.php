<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\Api\v1\ServiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/csrftoken', function () {
    return csrf_token();
});

Route::post('user/register', [AuthController::class, 'register'])->name('register');
Route::post('user/login', [AuthController::class, 'login'])->name('login');

Route::post('is-email-exist', [UserController::class, 'isEmailExist']);



Route::group(['middleware' => ['auth.jwt', 'auth.admin']], function () {
    Route::get('user', [UserController::class, 'getUsers'])->name('getUsers');
});

Route::group(['middleware' => ['auth.jwt']], function () {
    Route::get('user/myprofile', [AuthController::class, 'myprofile']);
    Route::post('user/logout', [AuthController::class, 'logout']);

    Route::post('service/buy', [ServiceController::class, 'buyServices'])->name('buyServices');

    Route::get('payment_methods', [PaymentMethodController::class, 'index']);

    Route::get('transaction', [TransactionController::class, 'getAllTransactions'])->name('getAllTransactions');
    Route::post('top_ups', [TopUpController::class, 'store']);
    Route::get('tips', [TipController::class, 'index']);

    Route::get('users', [UserController::class, 'show']);
    Route::get('users/{username}', [UserController::class, 'getUserByUsername']);
    Route::put('users', [UserController::class, 'update']);

    Route::get('wallets', [WalletController::class, 'index']);
});
