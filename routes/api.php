<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use API\SampleController;
use API\TicketController;
use API\DepartmentController;
use API\ServicesController;
use API\StudentNumberController;
use API\VideosController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/now-serving',  'API\SampleController@getNowServingTickets');
Route::post('/now-serving/{ticketId}/take', 'API\SampleController@callTicket');
Route::apiResource('sample', SampleController::class);
Route::apiResource('ticket', TicketController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('videos', VideosController::class);
Route::post('/ticket/call', 'API\SampleController@playTicket');
Route::post('/check-counter-can-take', 'API\SampleController@checkIfCounterCanTake');
// Route::resource('departments.services', 'Api\ServicesController')->only('index');
// Route::post('/tickets', [TicketController::class, 'store']);
Route::apiResource('studentnumber', StudentNumberController::class)->only([
    'show',
]);