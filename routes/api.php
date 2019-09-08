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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


Route::post('usermanagement/register/user', 'ApiController@apiUserRegistration')->middleware('apiauth');
Route::post('usermanagement/add/group', 'ApiController@apiGroupCreation')->middleware('apiauth');
Route::get('usermanagement/get/groups-list', 'ApiController@apiGetGroupList')->middleware('apiauth');
Route::get('usermanagement/get/roles-list', 'ApiController@apiGetListOfRoles')->middleware('apiauth');