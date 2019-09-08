<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, App\Role, App\Member, App\Group;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
	public function usersList(){

		$getUsersList = Member::where('status', 0)->orderBy('created_at', 'desc')->paginate(100);

		return view('home', ['users' => $getUsersList]);
	}


	public function createNewRole(){

		if (Role::where('id', \Auth::user()->role_id)->first()->slug != 'admin'){
			request()->session()->flash('roleCreateError', 'Only admin can create new roles');

			return back();
		}

		try{

			$validate = \Validator::make(request()->all(), [
				'roleName' => 'required',
				'slug' => 'required|unique:roles',
				'roleDetails' => 'required|min:10'
			],

			[
				'roleName.required' => 'Role must have a name',
				'roleSlug.required' => 'Role short name is required',
				'slug.unique' => 'Role slug has already been used',
				'roleDetails.required' => 'Please write briefly abouth this role',
				'roleDetails.min' => 'Minum character for role details is 10'
			]);


			if ($validate->fails()) {

			//dd(json_decode($validate->messages()));

				request()->session()->flash('roleCreateError', $validate->messages());

				return back();
			}


			$data = new Role;
			$data->name = request('roleName');
			$data->slug = request('slug');
			$data->details = request('roleDetails');




			$data->save();

			request()->session()->flash('roleCreateSuccess', 'New Role Created Successfully');
			return back();

		}catch(QueryException $e){

			request()->session()->flash('roleCreateError', $e->getMessage());
			return back();
		}


	}



	public function getNewGroup(){
		$getRoles = Role::orderBy('name', 'asc')->get();

		return view('new-group', ['roles' => $getRoles]);
	}


	public function createNewGroup(){

		if (Role::where('id', \Auth::user()->role_id)->first()->slug != 'admin'){
			request()->session()->flash('groupCreateError', 'Only admin can create group');

			return back();
		}

		try{

			$validate = \Validator::make(request()->all(), [
				'grpName' => 'required|unique:groups',
				'groupRole' => 'required',
				'grpDescription' => 'required|min:10'
			],

			[
				'grpName.required' => 'Group must have a name',
				'groupRole.required' => 'A group must have a role to perform',
				'grpName.unique' => 'Group with same name already exists',
				'grpDescription.required' => 'Please write briefly abouth this group',
				'grpDescription.min' => 'Minum character for group details is 10'
			]);


			if ($validate->fails()) {

			//dd(json_decode($validate->messages()));

				request()->session()->flash('groupCreateError', $validate->messages());

				return back();
			}


			$group = new Group;

			$group->grpName = request('grpName');
			$group->role_id = request('groupRole');
			$group->grpDescription = request('grpDescription');




			$group->save();

			request()->session()->flash('groupCreateSuccess', 'New Group Created Successfully');

			return back();

		}catch(QueryException $e){

			request()->session()->flash('groupCreateError', $e->getMessage());

			return back();
		}
	}




	public function getCreateUser() {
		$group = Group::where('grpStatus', 0)->orderBy('grpName', 'asc')->get();
		return view('new-user', ['groups' => $group]);
	}



	public function createNewUser(){


		if (Role::where('id', \Auth::user()->role_id)->first()->slug != 'admin'){
			request()->session()->flash('userCreateError', 'Only admin can add new users');

			return back();
		}

		try{

			$validate = \Validator::make(request()->all(), [
				'email' => 'required|email|unique:users',
				'name' => 'required|string',
				'phone' => 'required|unique:members',
				'group' => 'required'
			]);


			if ($validate->fails()) {
				request()->session()->flash('userCreateError', $validate->messages());

				return back();
			}


			$member = new Member;
			$member->group_id = request('group');
			$member->name = request('name');
			$member->phone = request('phone');
			$member->user_email = request('email');

			$member->save();

			if (request()->has('userLogin') && request('userLogin') == "YES") {

				$grp = Group::where('id', request('group'))->first();

				$usr = new User;
				$usr->role_id = $grp->role->id;
				$usr->name = request('name');
				$usr->email = request('email');
				$usr->password = \Hash::make(date('Ymd', time()));


				$usr->save();
			}



			request()->session()->flash('userCreateSuccess', 'New User Created Successfully');

			return back();


		}catch(QueryException $e){

			request()->session()->flash('userCreateError', $e->getMessage());

			return back();
		}
	}


	public function listAllGroups(){
		$groups = Group::orderBy('grpName', 'asc')->where('grpStatus', 0)->paginate(100);

		return view('groups', ['groups' => $groups]);
	}


	public function listAllRoles(){
		$getRoles = Role::orderBy('name', 'asc')->paginate(100);

		return view('roles', ['roles' => $getRoles]);
	}



	public function getSingleUserDetails($id){
		$getUser = Member::where('id', $id)->first();
		$groups = Group::where('grpStatus', 0)->orderBy('grpName', 'asc')->get();
		return view('user-edit', ['user' => $getUser, 'groups' => $groups]);
	}



	public function editUserDetails(){

		if (Role::where('id', \Auth::user()->role_id)->first()->slug != 'admin'){
			request()->session()->flash('userEditError', 'Only admin can edit users');

			return back();
		}

		try{

			$validate = \Validator::make(request()->all(), [
				'email' => 'required|email',
				'name' => 'required|string',
				'phone' => 'required',
				'group' => 'required'
			]);


			if ($validate->fails()) {
				request()->session()->flash('userEditError', $validate->messages());

				return back();
			}

			$data = [
				'group_id' => request('group'),
				'name' => request('name'),
				'phone' => request('phone'),
				'user_email' => request('email')
			];

			$updateMember = Member::where('id', request('member_id'))->update($data);


			if (request()->has('userLogin') && request('userLogin') == "YES" && User::where('id', request('user_id'))->exists()) {

				$grp = Group::where('id', request('group'))->first();

				$usr = new User;

				$userUpdate = [

					'role_id' => $grp->role->id,
					'name' => request('name'),
					'email' => request('email')
				];
				
				User::where('id', request('user_id'))->update($userUpdate);

			}
			//IF MEMBER WAS NOT A USER BEFORE AND THE CHECKBOX HAS BEEN CHECKED WHEN EDITING USER DETAILS
			else if(!User::where('id', request('user_id'))->exists() && request()->has('userLogin') && request('userLogin') == "YES"){

				$grp = Group::where('id', request('group'))->first();

				$usr = new User;
				$usr->role_id = $grp->role->id;
				$usr->name = request('name');
				$usr->email = request('email');
				$usr->password = \Hash::make(date('Ymd', time()));


				$usr->save();

			}

			//IF MEMBER WAS A USER BEFORE AND THE BOX HAS BEEN UNCHECKED, DELETE USER LOGIN ACCESS
			if (User::where('id', request('user_id'))->exists() && !request()->has('userLogin')) {
				
				User::where('id', request('user_id'))->delete();
			}



			request()->session()->flash('userEditSuccess', 'User details edited Successfully');

			return back();


		}catch(QueryException $e){

			request()->session()->flash('userCreateError', $e->getMessage());

			return back();
		}
	}
}
