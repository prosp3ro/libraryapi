<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// http://localhost/api/test
Route::get('/test', function () {
    return "it works";
});

// ex. http://localhost/api/auth/login
Route::group(['prefix' => 'auth'], function ($router) {
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/logout', [AuthController::class, 'logout']);
    $router->post('/refresh', [AuthController::class, 'refresh']);
    $router->get('/profile', [AuthController::class, 'myProfile']);

    $router->post('/admin/register', [AuthController::class, 'registerAdmin']);
});


// ex. http://localhost/api/books/add
Route::group(['prefix' => 'books', 'middleware' => ['admin']], function ($router) {
    $router->post('/add', [BookController::class, 'addBook']);
    $router->delete('/remove/{id}', [BookController::class, 'removeBook']);
    $router->put('/edit/{id}', [BookController::class, 'editBook']);
});
