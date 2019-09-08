<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{


	public function role(){
		return $this->hasOne('App\Role', 'id', 'role_id');
	}


	public function groupMembers(){
		return $this->hasMany('App\Member', 'group_id', 'id')->where('status', 0);
	}

}
