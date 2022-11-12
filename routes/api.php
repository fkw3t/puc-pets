<?php

use App\Models\Pet;
use App\Models\Vet;
use App\Models\User;
use App\Models\Expense;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\VetController;

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

    // Route::get('schedule/{id}/confirm/{hash}', [ScheduleController::class, 'confirm']);
    Route::get('schedule/{id}/confirm', [ScheduleController::class, 'confirm'])
        ->name('schedule.confirm')
        ->middleware('schedule.confirm');

    Route::group(['middleware' => 'jwt.auth'], function () {

        Route::post('logout', [AuthController::class ,'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);

        Route::apiResource('user', UserController::class)->except(['store']);
        Route::get('user/document/{document}', [UserController::class, 'showByDocument']);
        Route::get('user/{id}/pet', [UserController::class, 'pets']);

        Route::apiResource('vet', VetController::class);
        Route::get('vet/document/{document}', [VetController::class, 'showByCRM']);

        Route::apiResource('pet', PetController::class);

        Route::apiResource('schedule', ScheduleController::class);
        Route::get('schedule/open', [ScheduleController::class, 'open']);
        Route::get('schedule/pending', [ScheduleController::class, 'pending']);
        Route::get('schedule/confirmed', [ScheduleController::class, 'confirmed']);
        Route::get('schedule/canceled', [ScheduleController::class, 'canceled']);
        Route::post('schedule/{id}/assign', [ScheduleController::class, 'assign']);
        Route::post('schedule/{id}/cancel', [ScheduleController::class, 'cancel']);
    });

});


Route::get('/', function () {

    // date_default_timezone_set('America/Sao_Paulo');
    // $user = new User();
    // $user->name = 'Eliabner Teixera Marques';
    // $user->email = 'eliabner.marques@mail.com';
    // $user->document_id = '15196832602';
    // $user->phone = '31997467665';
    // $user->password = Hash::make('test123');
    // $user->save();

    // $user1 = new User();
    // $user1->name = 'Guilhermino';
    // $user1->email = 'mail@mail.com';
    // $user1->document_id = '15196832601';
    // $user1->phone = '31997467665';
    // $user1->password = Hash::make('test123');
    // $user1->save();

    // $vet = new Vet();
    // $vet->user_id = $user1->id;
    // $vet->crm = '123456';
    // $vet->specialization = 'Nutrição Animal';
    // $vet->save();

    // $schedule = new Schedule();
    // $schedule->vet_id = $vet->id;
    // $schedule->client_id = $user->id;
    // $schedule->date = new DateTime();
    // $schedule->save();

    // $teslinha = new Pet();
    // $teslinha->name = 'Teslinha';
    // $teslinha->type = 'dog';
    // $teslinha->owner_id = 1;
    // $teslinha->size = 'small';
    // $teslinha->save();

    // $zed = new Pet();
    // $zed->name = 'Zed';
    // $zed->type = 'cat';
    // $zed->owner_id = 1;
    // $zed->size = 'small';
    // $zed->save();


    // return [
    //     'user' => $user->schedules,
    //     'vet' => $vet->schedules
    // ];
});
