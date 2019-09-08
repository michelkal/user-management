<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, App\Role, App\Member, App\Group;
use Illuminate\Database\QueryException;

class DeletionController extends Controller
{
	public function adminDeleteUser($id){

		if (null != $id && $id == request('user')) {


			try{

				//ONLY USER WITH ADMIN ROLE CAN DELETE OTHER USERS
				if (Role::where('id', \Auth::user()->role_id)->first()->slug == 'admin') {

					Member::where('id', $id)->update(['status' => 1]);


					User::where('email', Member::where('id', $id)->first()->user_email)->delete();

					request()->session()->flash('deleteSuccess', 'User deleted successfully');

					return response()->json(['status' => 1, 'message' => 'User deleted successfully']);

				}else{

					request()->session()->flash('deleteError', 'Delete permission denied');

					return response()->json(['status' => 0, 'message' => 'Only admin can delete users']);
				}

			}catch(QueryException $e){

				request()->session()->flash('deleteError', $e->getMessage());

				return response()->json(['status' => 0, 'message' => $e->getMessage()]);
			}
		}else{
			request()->session()->flash('deleteError', 'Invalid parameter for deletion');

			return response()->json(['status' => 0, 'message' => 'Invalid parameter for deletion']);
		}
	}



	public function adminDeleteGroup($id){

		if (null != $id && $id == request('grp')) {


			try{

				//ONLY USER WITH ADMIN ROLE CAN DELETE GROUP
				if (Role::where('id', \Auth::user()->role_id)->first()->slug == 'admin') {

					//CHECK IF GROUP STILL HAS MEMBERS BEFORE DELETING
					if (Member::where('group_id', $id)->where('status', 0)->exists()) {
						
						request()->session()->flash('grpDeleteError', 'This group has active members and cannot be deleted');

						return response()->json(['status' => 0, 'message' => 'This group has active members and cannot be deleted']);

					}else{

						Group::where('id', $id)->update(['grpStatus' => 1]);

						request()->session()->flash('grpDeleteSuccess', 'Group Deleted successfully');

						return response()->json(['status' => 1, 'message' => 'Group Deleted successfully']);

					}

				}else{

					request()->session()->flash('grpDeleteError', 'Delete permission denied');

					return response()->json(['status' => 0, 'message' => 'Only admin can delete groups']);
				}

			}catch(QueryException $e){

				request()->session()->flash('grpDeleteError', $e->getMessage());

				return response()->json(['status' => 0, 'message' => $e->getMessage()]);
			}
		}else{
			request()->session()->flash('grpDeleteError', 'Invalid parameter for deletion');

			return response()->json(['status' => 0, 'message' => 'Invalid parameter for deletion']);
		}
	}



	public function adminDeleteRole($id){

		if (null != $id && $id == request('role')) {


			try{

				//ONLY USER WITH ADMIN ROLE CAN DELETE ROLE
				if (Role::where('id', \Auth::user()->role_id)->first()->slug == 'admin') {

					//CHECK IF ROLE STILL HAS GROUPS BEFORE DELETING
					if (Group::where('role_id', $id)->where('grpStatus', 0)->exists()) {
						
						request()->session()->flash('roleDeleteError', 'This role has active groups and cannot be deleted');

						return response()->json(['status' => 0, 'message' => 'This role has active groups and cannot be deleted']);

					}else{

						Role::where('id', $id)->delete();

						request()->session()->flash('roleDeleteSuccess', 'Role Deleted successfully');

						return response()->json(['status' => 1, 'message' => 'Role Deleted successfully']);

					}

				}else{

					request()->session()->flash('roleDeleteError', 'Delete permission denied');

					return response()->json(['status' => 0, 'message' => 'Only admin can delete Roles']);
				}

			}catch(QueryException $e){

				request()->session()->flash('roleDeleteError', $e->getMessage());

				return response()->json(['status' => 0, 'message' => $e->getMessage()]);
			}
		}else{
			request()->session()->flash('roleDeleteError', 'Invalid parameter for deletion');

			return response()->json(['status' => 0, 'message' => 'Invalid parameter for deletion']);
		}
	}
}
