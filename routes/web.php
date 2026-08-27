<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])
    ->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public routes - dapat diakses guest dan authenticated users
Route::get('/book/detail/{id}', [BookController::class, 'detail'])
    ->name('book.detail');

// Protected routes - hanya untuk authenticated users
Route::middleware('auth')->group(function () {
    // Routes untuk upload/manage buku - hanya untuk owner
    Route::middleware('owner')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/delete/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    // Routes untuk rental (cart, checkout) - hanya untuk renter
    Route::middleware('renter')->group(function () {
        Route::post('/cart/add/{id}', [BookController::class, 'addToCart'])->name('cart.add');
        Route::get('/cart', [BookController::class, 'cart'])->name('cart.index');
        Route::post('/cart/remove/{id}', [BookController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/cart/update-duration/{id}', [BookController::class, 'updateCartDuration'])->name('cart.update-duration');
        Route::post('/cart/checkout', [BookController::class, 'checkout'])->name('cart.checkout');
    });

    Route::get('/rentals', [BookController::class, 'rentals'])->name('rentals.index');
    Route::get('/dashboard', [BookController::class, 'dashboard'])->name('dashboard');
});

