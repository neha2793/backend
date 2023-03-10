<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NFTManagementController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\ShippingContainerController;
use App\Http\Controllers\ShippingContainerVideoController;
use App\Http\Controllers\SlimeSeatController;
use App\Http\Controllers\UserVerificationRequestController;
use App\Http\Controllers\SlimeTourController;
use App\Http\Controllers\PageController;

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

// Route::get('/', function(){
    
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('/logout',function(){
    if(auth()->check()) {
        \Auth::logout();
    }
    return redirect('/login');
})->name('admin-logout');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/user-management', [HomeController::class, 'UserList'])->name('user-management');

Route::resource('/user-management', UserManagementController::class);
Route::resource('/nft-management', NFTManagementController::class);
Route::resource('/order-management', OrderManagementController::class);
Route::resource('/shipping-container-management', ShippingContainerController::class);
Route::resource('/SC-video-management', ShippingContainerVideoController::class);
Route::resource('/slime-seat-management', SlimeSeatController::class);
Route::resource('/slime-tour', SlimeTourController::class);
Route::resource('/page-management', PageController::class);

Route::resource('/user-varification-request', UserVerificationRequestController::class);
Route::get('/transactions', [HomeController::class, 'Transactions'])->name('transactions-list');

