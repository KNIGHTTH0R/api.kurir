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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Response $response)
    {
        if(!$this->runValidation($request, $this->getValidationRules())){
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
        $user->email = $request->input('user.email') ? $request->input('user.email'): $user->email;
        $user->phone_number = $request->input('user.phone_number') ? $request->input('user.phone_number') : $user->phone_number;
        $user->type = $request->input('user.type') ? $request->input('user.type') : $user->type;
        $user->password = \App\Helper\Hashed\hash_password($request->input('user.password'));

        $user->save();
        return $response->setStatusCode(Codes::SUCCESS)->withArray([
            'success' => [
                'code' => Codes::SUCCESS,
                'message' => trans('users data successfully updated')
            ]
        ]);
    }

    private function getValidationRules(){
        $rules =  [
            'user.name' => 'required|max:150',
            'user.email' => 'required|max:250|email|unique:users,email',
            'user.phone_number' => 'required|max:15',
            'user.type' => 'required|in:' . UserModel::TYPE_ADMIN . ',' . UserModel::TYPE_CUSTOMER . ',' . UserModel::TYPE_KURIR,
            'user.password' => 'required|min:5',
            'user.confirm_password' => 'required|min:5|same:user.password',
        ];

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
