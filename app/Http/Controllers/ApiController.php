<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User, App\Role, App\Member, App\Group, App\ApiUser, App\ApiToken;
use Illuminate\Database\QueryException;

class ApiController extends Controller
{

	public function getApiList(){

		$getApiList = ApiUser::orderBy('created_at', 'desc')->paginate(100);

		return view('api-access', ['apis' => $getApiList]);
	}


	public function createNewApiKey(){
		try{

			$validateRequest = \Validator::make(request()->all(), [
				'orgName' => 'required|unique:api_users',
				'orgContact' => 'required'
			]);

			if ($validateRequest->fails()) {
				request()->session()->flash('apiCreateError', $validateRequest->messages());
				return back();
			}

			$details = [
				'orgName' => request('orgName'),
				'orgContact' => request('orgContact'),
				'grantBy' => \Auth::user()->name,
				'grantRole' => \Auth::user()->roles->slug,
				'orgCode' => 'INTER-'.rand(1, 100).'-'.strtoupper(str_replace(' ', '-', request('orgName')))
			];

			$apiToken = new ApiToken;
			$apiToken->accessKey = $this->generateApiToken($details)['jwt'];

			if ($apiToken->save()) {
				
				$apiUser = new ApiUser;
				$apiUser->api_token_id = $apiToken->id;
				$apiUser->orgName = request('orgName');
				$apiUser->orgCode = $details['orgCode'];
				$apiUser->orgContact = request('orgContact');

				$apiUser->save();


				request()->session()->flash('apiCreateSuccess', 'Successfully created new API token');
				return back();
			}

			

		}catch(QueryException $e){
			request()->session()->flash('apiCreateError', $e->getMessage());
			return back();
		}
	}


	public function generateApiToken($details){

		$response['status'] = 0;

		$secret = env("JWT_SECRET", "Th1sp0W3rfullW3bbT0k3NisS3cuRe");
		$secret .= md5($secret);

		$tokenHeader['alg'] = "HS256";
		$tokenHeader['typ'] = "JWT";

		$tokenPayload['org'] = $details['orgName'];
		$tokenPayload['grantId'] = $details['grantRole'];
		$tokenPayload['orgContact'] = $details['orgContact'];
		$tokenPayload['accessBy'] = $details['grantBy'];
		$tokenPayload['orgCode'] = $details['orgCode'];
		$tokenPayload['dev'] = rand(1, 1000);
		$tokenPayload['iss'] = Carbon::createFromFormat('Y-m-d H:i:s', now())->toDateTimeString();
		$tokenPayload['exp'] = Carbon::createFromFormat('Y-m-d H:i:s', now())->addMinutes(30)->toDateTimeString();
		$tokenPayload['age'] = "REST API";

		$tHeader = base64_encode(json_encode($tokenHeader));
		$tPayload = base64_encode(json_encode($tokenPayload));

		$signThis = $tHeader . $tPayload;

		$tSignature = hash_hmac('sha256', $signThis, $secret);

		$jwt = $tHeader . "." . $tPayload . "." . $tSignature;

		$response['status'] = 1;
		$response['jwt'] = $jwt;

		return $response;

	}


	public function validateApiToken($details){

		$response['status'] = 0;
		$response['message'] = "Invalid Authorization Token";

		$secret = env("JWT_SECRET", "Th1sp0W3rfullW3bbT0k3NisS3cuRe");
		$secret .= md5($secret);

		$theJWT = explode(".", $details['jwt']);

		$signThis = $theJWT[0] . $theJWT[1];

		$theMatch = hash_hmac('sha256', $signThis, $secret);

		if($theMatch === $theJWT[2]){

			$tokenHeader = json_decode(base64_decode($theJWT[0]));
			$tokenPayload = json_decode(base64_decode($theJWT[1]));

			$response['message'] = "Token Expired"; 

			/*TOKEN LIFE TIME IS SET TO 6 MONTHS WHICH IS 262800 IN MINUTES*/
			if(Carbon::createFromFormat('Y-m-d H:i:s', $tokenPayload->iss)->diffInMinutes(now(), false) < 262800){

				$response['status'] = 1;
				$response['message'] = "Valid Authorization Token";
				$response['payload'] = $tokenPayload;

			}



		}

		return $response;

	}



	/*ACCEPT API REQUEST*/
	public function apiUserRegistration(){

		$requestBody = json_decode(request()->getContent(), true);
		try{

			$validateRequest = \Validator::make($requestBody, [
				'email' => 'required|email|unique:users',
				'name' => 'required|string',
				'phone' => 'required|unique:members',
				'group' => 'required'
			]);

			if ($validateRequest->fails()) {
				$response = [
					'status' => 500,
					'message' => 'Request validation failed',
					'details' => $validateRequest->messages()
				];

				return response()->json($response);
			}

			$group = Group::where('grpName', $requestBody['group'])->first();

			if ($group) {

				$member = new Member;
				$member->group_id = $group->id;
				$member->name = $requestBody['name'];
				$member->phone = $requestBody['phone'];
				$member->user_email = $requestBody['email'];

				$member->save();

				if (isset($requestBody['userLogin']) && $requestBody['userLogin'] == "YES") {

					$usr = new User;
					$usr->role_id = $group->role->id;
					$usr->name = $requestBody['name'];
					$usr->email = $requestBody['email'];
					$usr->password = \Hash::make(date('Ymd', time()));


					$usr->save();
				}

				$response = [
					'status' => 1,
					'message' => 'User added Successfully',
					'details' => 'New user registration was succeseful'
				];

				return response()->json($response);

			}else{

				$response = [
					'status' => 4,
					'message' => 'Group ['.$requestBody['group']. '] does not exist',
					'details' => 'Cannot assign user to an unkown group'
				];

				return response()->json($response);
			}

		}catch(QueryException $e){

			$response = [
				'status' => 500,
				'message' => 'Unable to create new user',
				'details' => $e->getMessage()
			];

			return response()->json($response);
		}

	}


	/*CREATE NEW GROUP THROUGH API*/
	public function apiGroupCreation(){

		$requestBody = json_decode(request()->getContent(), true);

		try{

			$validate = \Validator::make($requestBody, [
				'grpName' => 'required|unique:groups',
				'groupRole' => 'required',
				'grpDescription' => 'required|min:10'
			]);


			if ($validate->fails()) {

				$response = [
					'status' => 500,
					'message' => 'Request validation failed',
					'details' => $validateRequest->messages()
				];

				return response()->json($response);
			}

			$role = Role::where('slug', $requestBody['groupRole'])->first();

			if ($role) {

				$group = new Group;

				$group->grpName = $requestBody['grpName'];
				$group->role_id = $role->id;
				$group->grpDescription = $requestBody['grpDescription'];
				$group->save();

				$response = [
					'status' => 1,
					'message' => 'Successfully Created New Group',
					'details' => 'New group registration was succeseful'
				];

				return response()->json($response);

			}else{

				$response = [
					'status' => 4,
					'message' => 'Role ['.$requestBody['groupRole']. '] does not exist',
					'details' => 'Cannot create group with an unkown role'
				];

				return response()->json($response);
			}

		}catch(QueryException $e){

			$response = [
				'status' => 500,
				'message' => 'Unable to create new group',
				'details' => $e->getMessage()
			];

			return response()->json($response);
		}
	}



	/*GET LIST OF GROUPS THROUGH API*/
	public function apiGetGroupList(){

		$getGroup = \DB::table('groups')->where('grpStatus', 0)
		->select(['grpName', 'grpDescription'])->orderBy('grpName', 'asc')->get();

		$response = [
			'status' => 1,
			'message' => 'Successfully Retrieved Groups List',
			'data' => [
				'groups' => $getGroup,
				'totalNumberOfGroups' => count($getGroup)
			]
		];


		return response()->json($response);
	}




	/*GET LIST OF ROLES THROUGH API*/
	public function apiGetListOfRoles(){

		$roles = \DB::table('roles')->select(['slug', 'details'])->orderBy('name', 'asc')->get();

		$response = [
			'status' => 1,
			'message' => 'Successfully Retrieved Roles List',
			'data' => [
				'roles' => $roles,
				'totalNumberOfRoles' => count($roles)
			]
		];


		return response()->json($response);
	}

}
