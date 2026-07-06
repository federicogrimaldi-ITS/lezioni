· PHP
<?php
 
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Support\UserSession;
use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return UserSession::isLoggedIn()
        ? redirect()->route('dashboard')
        : redirect()->route('login.form');
});
 
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login.store');
 
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [UserController::class, 'register'])->name('register.store');
 
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
 
Route::get('/dashboard', [UserController::class, 'dashboard'])
    ->middleware('auth.session')
    ->name('dashboard');
 
Route::get('/user', [UserController::class, 'index'])->name('user.index');
 
// Transazioni: tutte protette dalla stessa sessione custom della dashboard
Route::middleware('auth.session')->group(function () {
    Route::resource('transactions', TransactionController::class)
        ->except(['show']);
});
 