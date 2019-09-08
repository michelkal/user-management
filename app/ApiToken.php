<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
	protected $table = 'api_tokens';
	
	public function organization(){
		return $this->belongsTo('App\ApiUser', 'api_token_id', 'id');
	}
}
