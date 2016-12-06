<?php

namespace App;

use app\Libraries\Structure\SessionToken;
use Illuminate\Http\Request;
use PluginCommonSurvey\Libraries\ApiClient;
use PluginCommonSurvey\Libraries\Codes;

trait TraitAuth
{
    private function checkApiClientAndSecretCode(Request $request){
        return $request->client_id != ApiClient::CLIENT_ID || $request->secret_code != ApiClient::SECRET_CODE ? false : true;
    }

    private function getInvalidApiClientAndSecretCodeResponse(){
        return $this->response->errorWrongArgs([trans('invalid api client or secret code value')]);
    }

    private function getSuccessStoreResponse(SessionToken $sessionToken){
        return $this->response->setStatusCode(201)->withArray([
            'code' => Codes::SUCCESS,
            'access_token' => $sessionToken->getAttribute('access_token'),
            'refresh_token' => $sessionToken->getAttribute('refresh_token'),
            'user_id' => $sessionToken->getAttribute('user_id'),
            'user_type' => $sessionToken->getAttribute('user_type'),
            'hashed_password' => $sessionToken->getAttribute('hashed_password'),
            'token_type' => $sessionToken->getAttribute('token_type'),
            'expires_in' => $sessionToken->getAttribute('expires_in'),
        ]);
    }
}
