<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
	protected $table = 'api_users';

	public function tokenKey(){
		return $this->hasOne('App\ApiToken', 'id', 'api_token_id');
	}
}
