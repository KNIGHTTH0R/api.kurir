<?php

namespace App;

use App\Http\Requests;
use app\Libraries\Structure\SessionToken;
use Closure;
use PluginCommonSurvey\Libraries\Codes;

trait TraitPrivilegeMiddleware
{
    /**
     * @var SessionToken
     */
    private $sessionToken;

    private function checkIsValidToken(\Illuminate\Http\Request $request, Closure $next){
        /** @var SessionToken $sessionToken */
        $this->sessionToken = AuthToken::getInstanceFromAccessToken($request->bearerToken())->getSessionToken();
        return $this->sessionToken ? true : false;
    }

    private function responseInvalidToken(){
        return response()->json([
            'code' => Codes::INVALID_TOKEN,
            'message' => trans('your token is invalid or has been expired'),
        ])->setStatusCode(401);
    }

    private function responseUnathorizedAccess(){
        return response()->json([
            'code' => Codes::UNAUTHORIZED_ACCESS,
            'message' => trans('Unathorized access for this endpoint')
        ])->setStatusCode(401);
    }

    private function checkAccessByUserType(){
        /** TODO : you can override this return value on the route middleware */
        return true;
    }

    public function handle($request, Closure $next)
    {
        // Invalid Token
        if(!$this->checkIsValidToken($request, $next)){
            return $this->responseInvalidToken();
        }

        // Unauthorized access
        if(!$this->checkAccessByUserType()){
            return $this->responseUnathorizedAccess();
        }

        return $next($request);
    }
}
