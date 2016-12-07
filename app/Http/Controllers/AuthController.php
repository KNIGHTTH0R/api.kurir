<?php

namespace App\Http\Controllers;

use App\TraitAuth;
use App\TraitValidate;
use Illuminate\Http\Request;
use App\Users as UsersModel;
use App\AuthToken;
use app\Libraries\Structure\SessionToken;
use EllipseSynergie\ApiResponse\Contracts\Response;

class AuthController extends Controller
{
    use TraitValidate;

    use TraitAuth;

    /**
     * @var Response
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    // TODO this has been deprecated
    public function store(Request $request){
        if(!$this->runValidation($request, [
            'user_id' => 'required|integer|exists:users,id',
            'client_id' => 'required',
            'secret_code' => 'required',
        ])){
            return $this->response->errorWrongArgs($this->validator->errors()->all());
        }

        if(!$this->checkApiClientAndSecretCode($request)){
            return $this->getInvalidApiClientAndSecretCodeResponse();
        }

        try {
            /** @var SessionToken $sessionToken */
            $sessionToken = AuthToken::getFreshInstance($request->user_id)->getSessionToken();
        }catch(\Exception $e){
            return $this->response->errorInternalError($e->getMessage());
        }

        return $this->getSuccessStoreResponse($sessionToken);
    }

    // TODO still need to implement this
    public function refresh(Request $request){
        if(!$this->runValidation($request, [
            'refresh_token' => 'required',
        ])){
            return $this->response->errorWrongArgs([$this->validator->errors()->all()]);
        }

        try{
            /** @var SessionToken $sessionToken */
            $sessionToken = AuthToken::getInstanceFromRefreshToken($request->refresh_token)->getSessionToken();
        }catch(\Exception $e){
            return $this->response->errorInternalError($e->getMessage());
        }

        if(!$sessionToken){
            return $this->response->errorInternalError([trans('refresh token is invalid')]);
        }

        return $this->getSuccessStoreResponse($sessionToken);
    }

    public function grantpassword(Request $request){
        if(!$this->runValidation($request, [
            'email' => 'required|max:50|email|exists:users,email',
            'password' => 'required|min:5',
            'client_id' => 'required',
            'secret_code' => 'required'
        ])){
            return $this->response->errorInternalError($this->validator->errors()->all());
        }

        if(!$this->checkApiClientAndSecretCode($request)){
            return $this->getInvalidApiClientAndSecretCodeResponse();
        }

        $user = UsersModel::where('email', $request->email)
            ->where('password', \App\Helper\Hashed\hash_password($request->password))
            ->first();

        if(empty($user) || count($user) === 0){
            return $this->response->errorNotFound([trans('login.failed')]);
        }

        try {
            /** @var SessionToken $sessionToken */
            $sessionToken = AuthToken::getFreshInstance($user->id, \App\Helper\Hashed\hash_password($request->password))->getSessionToken();
        }catch(\Exception $e){
            return $this->response->errorInternalError($e->getMessage());
        }

        return $this->getSuccessStoreResponse($sessionToken);
    }

    public function grantpasswordhashed(Request $request){
        if(!$this->runValidation($request, [
            'user_id' => 'required|integer|exists:users,id',
            'hashed_password' => 'required',
            'client_id' => 'required',
            'secret_code' => 'required'
        ])){
            return $this->response->errorInternalError($this->validator->errors()->all());
        }

        if(!$this->checkApiClientAndSecretCode($request)){
            return $this->getInvalidApiClientAndSecretCodeResponse();
        }

        $user = UsersModel::find($request->user_id);

        if($user->password != $request->hashed_password){
            return $this->response->errorWrongArgs([trans('invalid password')]);
        }

        if(empty($user) || count($user) === 0){
            return $this->response->errorNotFound([trans('login.failed')]);
        }

        try {
            /** @var SessionToken $sessionToken */
            $sessionToken = AuthToken::getFreshInstance($user->id, $request->hashed_password)->getSessionToken();
        }catch(\Exception $e){
            return $this->response->errorInternalError($e->getMessage());
        }

        return $this->getSuccessStoreResponse($sessionToken);
    }
}
