<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\NFTController;
use App\Http\Controllers\Api\ShippingContainerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SlimeSeatController;
use App\Http\Controllers\Api\WishlistController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('check-auth', [ApiAuthController::class, 'CheckAuth']);

    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::post('/save-wallet-address',[NFTController::class, 'saveWalletAddress']);

    // Upload nft 
    Route::get('/get-users-nft',[NFTController::class, 'UserNFT']);
    Route::post('/save-user-nft',[NFTController::class, 'saveUserNFT']);
    // Buy Nft
    Route::get('/get-users-buy-nft',[NFTController::class, 'BuyNFTUser']);
    Route::post('/user-buy-nft',[NFTController::class, 'saveBoughtNFT']);
    Route::post('/get-user-nft-collections',[NFTController::class, 'userNFTCollection']);
    Route::get('/get-users',[NFTController::class, 'users']);
    
    // profile
    Route::get('/my-profile',[NFTController::class, 'MyProfile']);
    Route::post('/update-my-profile',[NFTController::class, 'UpdateMyProfile']);

    // SC Video Upload
    Route::post('/sc-video', [ShippingContainerController::class, 'SCVideo']);
    Route::get('/get-sc-video', [ShippingContainerController::class, 'SCVideoList']);
    Route::post('/sc-video-delete', [ShippingContainerController::class, 'SCVideoDelete']);

    // Manage SC Upload
    Route::post('/manage-sc', [ShippingContainerController::class, 'ManageSC']);
    Route::post('/manage-sc-delete', [ShippingContainerController::class, 'ManageSCDelete']);
    Route::post('/get-manage-sc-list', [ShippingContainerController::class, 'ManageSCList']);

    // Manage Shipping Container Edit page values with sc id 
    Route::post('/get-manage-sc', [ShippingContainerController::class, 'GetManageSC']);
    Route::post('/update-manage-sc', [ShippingContainerController::class, 'UpdateManageSC']);

    // Map Shipping Container Video
    Route::post('/map-shipping-container-video', [ShippingContainerController::class, 'MapSCVideo']);

    // Map Manage Shipping Container 
    Route::post('/map-manage-shipping-container', [ShippingContainerController::class, 'MapManageSC']);

    // Slime Seat Management
    Route::post('/add-slime-seat', [SlimeSeatController::class, 'AddSlimeSeat']);
    Route::get('/get-slime-seat-list', [SlimeSeatController::class, 'GetSlimeSeat']);
    Route::post('/slime-seat-delete', [SlimeSeatController::class, 'DeleteSlimeSeat']);
    Route::post('/get-slime-seat-wid', [SlimeSeatController::class, 'GetSlimeSeatWithID']);
    Route::post('/update-slime-seat', [SlimeSeatController::class, 'UpdateSlimeSeat']);

    // map slime seat
    Route::post('/map-slime-seat', [SlimeSeatController::class,'MapSlimeSeat']);
    Route::get('/get-transaction-history', [OrderController::class, 'GetTransactionHistory']);

    // Wishlist
    Route::post('/add-wishlist', [WishlistController::class, 'AddWishlist']);
    Route::get('/get-wishlist', [WishlistController::class, 'Wishlist']);
    Route::post('/remove-wishlist', [WishlistController::class, 'DeleteWishlist']);

    // All NFT Profile Images
    Route::post('/nft-profile-image', [NFTController::class, 'NFTProfileImage']);

    //Order Place
    Route::post('/order-nft', [OrderController::class, 'Ordernft']);
    
    // Verify User
    Route::post('/verification-request', [ApiAuthController::class, 'VerifyUser']);
});

// MY Profile Public View
Route::get('/get-profile', [ApiAuthController::class, 'GetProfile']);

// Slime Tour
Route::get('/get-slime-tour', [SlimeSeatController::class, 'GetSlimeTour']);

// Pages
Route::get('/get-pages', [ApiAuthController::class, 'GetPages']);
// Based On Page ID
Route::get('/get-page', [ApiAuthController::class, 'GetPage']);

// Tranding MERCHANDISE
Route::get('/get-trending-merchandise', [NFTController::class, 'GetTrendingMerchandise']);

// contact us
Route::get('/get-contact-us', [ApiAuthController::class, 'GetContactUS']);
Route::post('/save-contact-us', [ApiAuthController::class, 'SaveContactUS']);


// Shipping Container List 
Route::get('/get-shipping-container', [ShippingContainerController::class, 'GetShippingContainer']);

//Shipping Visit container Update
Route::post('/update-shipping-visit-container', [ShippingContainerController::class, 'UpdateShippingVisitContainer']);

// Get Container content
Route::get('/get-container-content', [ShippingContainerController::class, 'GetContainerContent']);    // Produt Detail( NFT Info)

// Get Sliem Seat Container
Route::get('/get-slime-seat-container', [SlimeSeatController::class, 'GetSlimeSeatContainer']);    // Produt Detail( NFT Info)

//Order Place
Route::post('/order-place', [OrderController::class, 'OrderPlace']);

Route::post('/nft-profile-image-list', [NFTController::class, 'NFTProfileImageList']);


Route::post('/login', [ApiAuthController::class,'login']);
Route::post('/register', [ApiAuthController::class,'register']);

// Forget Password
Route::post('forget-password', [ApiAuthController::class, 'ForgetPassword']);
Route::post('confirm-reset-password', [ApiAuthController::class,'ResetPassword']);

// Top Seller
Route::get('/get-top-seller', [NFTController::class, 'GetTopSeller']);

// Top Create
Route::get('/get-top-creator', [NFTController::class, 'GetTopCreator']);

