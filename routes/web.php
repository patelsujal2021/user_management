<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;

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

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/',[AuthController::class,'loginPage'])->name('auth.login.page');
Route::post('/login',[AuthController::class,'loginProcess'])->name('auth.login');
Route::get('/logout',[AuthController::class,'logoutProcess'])->name('auth.logout');

Route::get('/register',[AuthController::class,'registrationPage'])->name('auth.register.page');
Route::post('/register',[AuthController::class,'registrationProcess'])->name('auth.register');
Route::get('/verify/register/{email}/{token}', [AuthController::class,'verifyRegistrationProcess'])->name('auth.verify.register');

Route::group(['prefix'=>'account','middleware'=>['auth','prevent-back-history']],function(){
    Route::get('dashboard',[DashboardController::class,'dashboard'])->name('account.dashboard');

    Route::resource('user',UserController::class);

    Route::resource('blog',BlogController::class);
});
