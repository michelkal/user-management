<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public function groupRoles(){
		return $this->hasMany('App\Group', 'role_id', 'id');
	}


	public function usersRole(){
		return $this->hasMany('App\User', 'role_id', 'id');
	}
}
