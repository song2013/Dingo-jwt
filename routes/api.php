<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['middleware' => ['jwt.api.auth']], function ($api) {
        $api->post('user/register', 'App\Api\Controllers\AuthController@register');
        $api->post('user/authenticate', 'App\Api\Controllers\AuthController@authenticate');
        $api->post('user/AuthenticatedUser', 'App\Api\Controllers\AuthController@AuthenticatedUser');

        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            $api->get('index', 'App\Api\Controllers\v1\IndexController@show');
        });
    });
});