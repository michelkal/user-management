<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
	public function userLoginAccess(){


		/*VALIDATE THE INPUT USER (ADMIN) PROVIDED FOR LOGIN*/
		$validation = \Validator::make(request()->all(), [

			'username' => 'required',
			'password' => 'required'
		], [
			'username.required' => 'Username cannot be empty',
			'password.required' => 'Password field cannot be empty'

		]);


		if ($validation->fails()) {
			request()->session()->flash('loginError', 'Username and Password are required');
			return back();
		}


		/*USERNAME AND PASSWORD WERE PROVIDED - PROCEED TO LOG USER IN*/
		$credentials = ['email' => request('username'), 'password' => request('password')];
		if (Auth::attempt($credentials)) {
			return redirect()->intended('admin/home');
		}else{
			request()->session()->flash('loginError', 'Invalid username or password');
			return back();
		}

	}
}
