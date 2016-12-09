<?php

namespace App\Http\Controllers;

use App\TraitValidate;
use App\Users as UserModel;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use PluginCommonKurir\Libraries\Codes;

class UsersController extends BaseController
{
    use TraitValidate;

    /**
     * @var bool
     */
    private $skipEmailChecking = false;

    /**
     * @var bool
     */
    private $skipPasswordChecking = false;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Response $response
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Response $response)
    {
        if (!$this->runValidation(
            $request->all(),
            $this->getValidationRules()
        )) {
            return $response->errorInternalError($this->validator->errors()->all());
        }

        /** @var UserModel $user */
        $user = new UserModel;

        $user->name = $request->input('user.name');
        $user->email = $request->input('user.email');
        $user->phone_number = $request->input('user.phone_number');
        $user->type = $request->input('user.type');
        $user->password = \App\Helper\Hashed\hash_password($request->input('user.password'));

        $user->save();

        return $response->setStatusCode(Codes::SUCCESS)->withArray([
            'success' => [
                'code' => Codes::SUCCESS,
                'message' => trans('users data successfully saved')
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Response $response
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Response $response, $id)
    {
        /** @var UserModel $user */
        $user = UserModel::find(['id' => $id])->first();

        if (is_null($user)) {
            return $response->errorNotFound(trans('user not found'));
        }

        $user->name = $request->input('user.name') ? $request->input('user.name') : $user->name;
        $user->phone_number = $request->input('user.phone_number') ? $request->input('user.phone_number') : $user->phone_number;
        $user->type = $request->input('user.type') ? $request->input('user.type') : $user->type;

        // determine if password checking is skipped
        $inputPassword = $request->input('user.password');
        if ($inputPassword) {
            $user->password = \App\Helper\Hashed\hash_password($inputPassword);
        } else {
            $this->skipPasswordChecking = true;
        }

        // determine if email checking is skipped
        $inputEmail = $request->input('user.email');
        if (!$inputEmail || ($inputEmail && $user->email === $inputEmail)) {
            $this->skipEmailChecking = true;
        } else {
            $user->email = $inputEmail;
        }

        if (!$this->runValidation(
            ['user' => array_merge(
                $user->toArray(),
                ['confirm_password' => \App\Helper\Hashed\hash_password($request->input('user.confirm_password'))]
            )],
            $this->getValidationRules()
        )) {
            return $response->errorInternalError($this->validator->errors()->all());
        }

        $user->save();

        return $response->setStatusCode(Codes::SUCCESS)->withArray([
            'success' => [
                'code' => Codes::SUCCESS,
                'message' => trans('users data successfully updated')
            ]
        ]);
    }

    protected function getValidationRules()
    {
        $rules =  [
            'user.name' => 'required|max:150',
            'user.phone_number' => 'required|max:15',
            'user.type' => 'required|in:' . UserModel::TYPE_ADMIN . ',' . UserModel::TYPE_CUSTOMER . ',' . UserModel::TYPE_KURIR,
            'user.password' => 'required|min:5',
        ];

        if ($this->skipPasswordChecking === false) {
            $rules['user.confirm_password'] = 'required|min:5|same:user.password';
        }

        if ($this->skipEmailChecking === false) {
            $rules['user.email'] = 'required|max:250|email|unique:users,email';
        }

        return $rules;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    protected function getModelName()
    {
        return \App\Users::class;
    }

    protected function getModelLabel()
    {
        return 'users';
    }

    protected function getTransformer()
    {
        return new \App\Transformer\Users();
    }
}
