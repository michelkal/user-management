<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
	public function group(){
		return $this->hasOne('App\Group', 'id', 'group_id');
	}


	public function isUser(){
		return $this->hasOne('App\User', 'email', 'user_email');
	}

}
