<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;

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

// Auth Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/generatetoken/{phoneNumber}', [AuthController::class, 'generateToken']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::match(['delete', 'post'], '/delete/account', [AuthController::class, 'deleteAccount']);
    });
});

// User Routes
Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
    Route::get('/me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::get('/allUsers', [\App\Http\Controllers\UserController::class, 'getAllUsers']);
    Route::post('/update-push-token', [\App\Http\Controllers\UserController::class, 'updatePushToken']);
    Route::match(['put', 'post'], '/update', [\App\Http\Controllers\UserController::class, 'updateProfile']);
});

// Notification Routes
Route::group(['prefix' => 'notification'], function () {
    Route::get('/get/all', [\App\Http\Controllers\NotificationController::class, 'getAll']);
    Route::get('/getById/{id}', [\App\Http\Controllers\NotificationController::class, 'getById']);
    Route::get('/user', [\App\Http\Controllers\NotificationController::class, 'getUserNotifications']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/add', [\App\Http\Controllers\NotificationController::class, 'add']);
        Route::match(['put', 'post'], '/updateById/{id}', [\App\Http\Controllers\NotificationController::class, 'update']);
        Route::match(['delete', 'post'], '/deleteById/{id}', [\App\Http\Controllers\NotificationController::class, 'delete']);
        Route::match(['put', 'post'], '/markRead/{id}', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    });
});

// Menu Routes
Route::group(['prefix' => 'menu'], function () {
    Route::get('/categories', [MenuController::class, 'getAllCategories']);
    Route::get('/items', [MenuController::class, 'getAllItems']);
    Route::get('/items/{categoryId}', [MenuController::class, 'getItemsByCategory']);
    Route::get('/items/popular', [MenuController::class, 'getPopularItems']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/items', [MenuController::class, 'addItem']);
        Route::match(['put', 'post'], '/items/{id}', [MenuController::class, 'updateItem']);
        Route::match(['delete', 'post'], '/items/delete/{id}', [MenuController::class, 'deleteItem']);

        Route::post('/categories', [MenuController::class, 'addCategory']);
        Route::match(['put', 'post'], '/categories/{id}', [MenuController::class, 'updateCategory']);
        Route::match(['delete', 'post'], '/categories/delete/{id}', [MenuController::class, 'deleteCategory']);
    });
});

// Reservation Routes
Route::group(['prefix' => 'reservations'], function () {
    Route::get('/getByPhone/{phoneNumber}', [ReservationController::class, 'getByPhone']);
    Route::get('/getById/{id}', [ReservationController::class, 'getById']);
    Route::get('/getAll', [ReservationController::class, 'getAll']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/add', [ReservationController::class, 'addReservation']);
        Route::match(['put', 'post'], '/update/{id}', [ReservationController::class, 'updateReservation']);
        Route::match(['delete', 'post'], '/delete/{id}', [ReservationController::class, 'cancelReservation']);
        Route::match(['put', 'post'], '/confirm/{id}', [ReservationController::class, 'confirmReservation']);
    });
});

// Review Routes
Route::group(['prefix' => 'reviews'], function () {
    Route::get('/', [ReviewController::class, 'getAllReviews']);
    Route::get('/latest', [ReviewController::class, 'getLatestReviews']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/add/{phoneNumber}', [ReviewController::class, 'addReview']);
        Route::match(['delete', 'post'], '/delete/{id}', [ReviewController::class, 'deleteReview']);
    });
});

// Restaurant Info Routes
Route::group(['prefix' => 'restaurant-info'], function () {
    Route::get('/get/all', [\App\Http\Controllers\RestaurantInfoController::class, 'getAll']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::match(['put', 'post'], '/update/{id}', [\App\Http\Controllers\RestaurantInfoController::class, 'update']);
    });
});

// Reward Routes (Front-end uses /api/rwdpts)
Route::group(['prefix' => 'rwdpts'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/addrwdpts', [RewardController::class, 'addPoints']);
        Route::get('/getrwdpts', [RewardController::class, 'getPoints']);
    });
});

// Chef Routes
Route::group(['prefix' => 'chefs'], function () {
    Route::get('/', [ChefController::class, 'getAll']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [ChefController::class, 'add']);
        Route::match(['delete', 'post'], '/{id}', [ChefController::class, 'delete']);
    });
});

// Gallery Routes
Route::group(['prefix' => 'gallery'], function () {
    Route::get('/', [GalleryController::class, 'getAll']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [GalleryController::class, 'add']);
        Route::match(['delete', 'post'], '/{id}', [GalleryController::class, 'delete']);
    });
});

// Order Routes
Route::group(['prefix' => 'orders'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/add', [OrderController::class, 'placeOrder']);
        Route::get('/mine', [OrderController::class, 'getMyOrders']);
        Route::get('/all', [OrderController::class, 'getAllOrders']);
        Route::match(['put', 'post'], '/update-status/{id}', [OrderController::class, 'updateStatus']);
    });
});
