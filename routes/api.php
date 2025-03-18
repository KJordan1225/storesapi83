<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
    $api->get ('hello', function() {
        return 'Hello Stores API 8.3' ;
    });

    $api->group(['prefix' => 'auth'], function($api) {
        $api->post('/signup', 'App\Http\Controllers\UserController@store');
        $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login')->name('login');       

        $api->group( ['middleware' => 'auth'], function($api) {
            $api->post('/token/refresh','App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout','App\Http\Controllers\Auth\AuthController@logout');
        });
    });

    $api->group(['middleware' => ['role:super-admin'], 'prefix' => 'admin' ],
        function ($api) {
            $api->get('users', 'App\Http\Controllers\Admin\AdminUserController@index');
        }
    );

    $api->group( ['prefix' => 'me', 'middleware' => 'auth' ],
        function ($api) {
            $api->get ('/profile', 'App\Http\Controllers\UserProfileController@index');
            $api->post('/profile', 'App\Http\Controllers\UserProfileController@store');
            $api->put('/profile', 'App\Http\Controllers\UserProfileController@update');
            $api->delete('/profile', 'App\Http\Controllers\UserProfileController@destroy');
        }
    );
});
