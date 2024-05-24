<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// GUEST
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/', 'index')->name('login');
        Route::get('/login', 'index')->name('login');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerUser');
        Route::post('/verify', 'verify');
    });
});

// AUTHENTICATE
//Admin
Route::middleware('token.auth')->group(function () {
    //Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                'Dashboard' => [
                    'icon' => 'dashboard',
                    'link' => 'dashboard'
                ]
            ]
        ]);
    });

    Route::get('/withdraw', [TransactionController::class, 'withdraw']);
    Route::get('/deposit', [TransactionController::class, 'deposit']);
    Route::get('/history', [TransactionController::class, 'history']);
    Route::get('/history-transaction', [TransactionController::class, 'getHistory']);
    Route::get('/balance', [TransactionController::class, 'getBalance']);
    Route::post('/withdraw', [TransactionController::class, 'store']);
    Route::post('/deposit', [TransactionController::class, 'store']);

    //Logout
    Route::post('/logout', [LoginController::class, 'logout']);
});
