<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\ApiController;
use \App\ApiUser;

class APILock
{
    /**
     * Handle an incoming request from API call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response['status'] = 0;
        $response['message'] = "Header must contain authorization token and orgization code";

        $theHeaders = $request->header();

        if(isset($theHeaders['authorization'][0]) && isset($theHeaders['org-code'][0])){

            $user = new ApiController;

            //Remove Bearer 

            $details['jwt'] = substr($theHeaders['authorization'][0], 7);
            
            $validateToken = $user->validateApiToken($details);

            if(isset($validateToken['status']) && isset($validateToken['payload']) && isset($validateToken['payload']->orgCode) && $validateToken['status'] === 1){

                if ($validateToken['payload']->grantId !== 'admin') {

                   $response['status'] = 403;
                   $response['message'] = 'Your API token was generated by an authorized user. Please contact system admin';

               }else{
                   $tokenUserId = $validateToken['payload']->orgCode;

                   $org = ApiUser::where('orgCode', $tokenUserId)->first();
                   if ($org && $theHeaders['org-code'][0] === $org->orgCode) {

                    return $next($request);

                }else{

                    $response['status'] = 403;
                    $response['message'] = "Orgization code mismatch"; 
                }
            }
        }

        $response['status'] = 401;
        $response['message'] = $validateToken['message'];

    }

    return response()->json($response);

}
}
