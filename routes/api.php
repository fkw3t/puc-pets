<?php

use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;

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

Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('register', [UserController::class, 'store']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class ,'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);


    Route::group(['middleware' => 'jwt.auth'], function () {

        Route::apiResource('user', UserController::class)->except(['store']);
        Route::get('user/document/{document}', [UserController::class, 'showByDocument']);

    });

});


Route::get('/', function () {

    // date_default_timezone_set('America/Sao_Paulo');
    // $user = new User();
    // $user->name = 'Eliabner Teixera Marques';
    // $user->email = 'eliabner.marques@mail.com';
    // $user->document_id = '15196832602';
    // $user->person_type = 'fisical';
    // $user->phone = '31997467665';
    // $user->password = Hash::make('test123');
    // $user->save();


    // $expense = new Expense();
    // $expense->description = 'dinner';
    // $expense->occurred_in = new DateTime();
    // $expense->user_id = $user->id;
    // $expense->amount = 55.8;
    // $expense->save();

    // $expense = new Expense();
    // $expense->description = 'lunch';
    // $expense->occurred_in = new DateTime();
    // $expense->user_id = $user->id;
    // $expense->amount = 72.5;
    // $expense->save();

    // return [
    //     'user' => $user,
    //     'expenses' => $user->expenses
    // ];
});
