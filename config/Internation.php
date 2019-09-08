<?php
namespace UserManagement\Internation\Services;

/**
 * @author Michel Kalavanda
 * @version 1.0.0 - User management code challenge
 * @copyright Internation 2019
 */

use App\User, App\Role, App\Member, App\Group;
use Illuminate\Database\QueryException;
class Internation
{
	
	public static function getUserPerGroup($groupId){
		return Member::whereIn('group_id', [$groupId])->count();
	}
}