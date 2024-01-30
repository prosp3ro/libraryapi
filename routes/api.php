<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Models\Book;
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

// TODO  move middleware logic from a controller here
// ex. http://localhost/api/auth/login
Route::group(['prefix' => 'auth'], function ($router) {
    $router->post('/login', [AuthController::class, 'login']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/logout', [AuthController::class, 'logout']);
    $router->post('/refresh', [AuthController::class, 'refresh']);
    $router->get('/profile', [AuthController::class, 'myProfile']);

    $router->post('/admin/register', [AuthController::class, 'registerAdmin']);
});

Route::group(['prefix' => 'books', 'middleware' => 'auth:api'], function ($router) {
    // Available for both admin and not admin
    // ex. http://localhost/api/books
    $router->get("/", [BookController::class, "index"]);
    $router->get("/available", [BookController::class, "showAvailableBooks"]);

    $router->middleware(['admin'])->group(function ($router) {
        // Available only for admin
        // ex. http://localhost/api/books/add
        $router->post('/add', [BookController::class, 'addBook']);
        $router->delete('/remove/{id}', [BookController::class, 'removeBook']);
        $router->put('/edit/{id}', [BookController::class, 'editBook']);
    });

    $router->middleware(['not_admin'])->group(function ($router) {
        // Available only for not admin
        // ex. http://localhost/api/books/borrow/1
        // TODO should be connected to the user borrowing a book (NOTE: user book relationship with users_books migration)
        $router->post('/borrow/{id}', [BookController::class, 'borrowBook']);
        // TODO only a user that has borrowed a certain book can return it
        $router->post('/return/{id}', [BookController::class, 'returnBook']);
    });
});
