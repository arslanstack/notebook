<?php

use App\Http\Controllers\Admin\AdminController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\DB;

Route::get('/migrate', function () {
	Artisan::call('migrate');
	return 'DONE'; // Return anything
});
Route::get('/migrate-refresh', function () {
	Artisan::call('migrate:refresh');
	return 'DONE'; // Return anything
});
Route::get('/migrate-rollback', function () {
	Artisan::call('migrate:rollback');
	return 'DONE'; // Return anything
});
Route::get(
	'/',
	function () {
		return redirect()->route('admin.dashboard');
	}
);

Route::get('/clearcache', function () {
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:cache');
	$exitCode = Artisan::call('view:clear');
	$exitCode = Artisan::call('route:clear');
	return 'DONE'; //Return anything
});



Route::group(['prefix'  =>  'admin'], function () {
	Route::get('login', [AuthController::class, 'index'])->name('login');
	Route::post('verify_login', [AuthController::class, 'verify_login']);
	Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:admin');

	Route::group(['middleware' => ['auth:admin']], function () {

		Route::get('/', [AdminController::class, 'index'])->middleware('auth:admin')->name('admin.dashboard');
		Route::get('admin', [AdminController::class, 'index'])->middleware('auth:admin');
		Route::get('change_password', [AdminController::class, 'change_password'])->middleware('auth:admin');
		Route::post('update_password', [AdminController::class, 'update_password'])->middleware('auth:admin');


		
		Route::group(['prefix'  =>  'users'], function () {
			Route::get('/', [UserController::class, 'index']);
			Route::post('/update_statuses', [UserController::class, 'update_status']);
			Route::get('detail/{id}', [UserController::class, 'details']);
		});
	});
});
