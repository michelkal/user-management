<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::get('login',['as' => 'login', 'uses' =>  function () {

	return view('login');

}]);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {

	Route::get('/home', 'AdminController@usersList');
	Route::get('new/user', 'AdminController@getCreateUser');

	Route::get('create/role', function() {
		return view('new-role');
	});

	Route::get('crt/new/group', 'AdminController@getNewGroup');
	Route::get('groups', 'AdminController@listAllGroups');
	Route::get('roles', 'AdminController@listAllRoles');
	Route::get('user/edit/{id}', 'AdminController@getSingleUserDetails');
	Route::get('api', 'ApiController@getApiList');
	Route::get('create/api/access', function(){
		return view('new-api-access');
	});



	/*ADMIN POST ROUTES*/
	Route::post('new-role', 'AdminController@createNewRole');
	Route::post('new-group', 'AdminController@createNewGroup');
	Route::post('new-user', 'AdminController@createNewUser');
	Route::post('edit-user', 'AdminController@editUserDetails');
	Route::post('new-api-key', 'ApiController@createNewApiKey');


	/*DELETING OBJECT*/
	Route::post('/dlt-usr/{id}', 'DeletionController@adminDeleteUser');
	Route::post('/grp-delete/{id}', 'DeletionController@adminDeleteGroup');
	Route::post('role-delete/{id}', 'DeletionController@adminDeleteRole');

});


Route::get('logout', function() {
	\Auth::logout();
	return redirect('/');
});


/*POST ROUTES*/
Route::post('/access', 'AuthenticationController@userLoginAccess');

