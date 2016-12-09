<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CustomerPrivilegeMiddleware;
use App\Http\Middleware\KurirPrivilegeMiddleware;
use app\Libraries\Structure\SessionToken;
use App\TraitValidate;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Items as ItemsModel;
use PluginCommonKurir\Libraries\Codes;

class ItemsController extends BaseController
{

    use TraitValidate;

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

        /** @var ItemsModel $item */
        $item = new ItemsModel();

        $item->name = $request->input('item.name');
        $item->receiver_name = $request->input('item.receiver_name');
        $item->receiver_phone_number = $request->input('item.receiver_phone_number');
        $item->pickup_address = $request->input('item.pickup_address');
        $item->destination_address = $request->input('item.destination_address');
        $item->id_customer = $request->input('item.id_customer');
        $item->id_kurir = $request->input('item.id_kurir');

        $item->save();

        return $response->setStatusCode(Codes::SUCCESS)->withArray([
            'success' => [
                'code' => Codes::SUCCESS,
                'message' => trans('item data successfully saved')
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
        /** @var ItemsModel $item */
        $item = $request->item;

        /** @var SessionToken $sessionToken */
        $sessionToken = $request->loggedUser;

        if (is_null($item)) {
            return $response->errorNotFound(trans('item not found'));
        }


        if (
            $sessionToken->getUserType() === KurirPrivilegeMiddleware::USER_TYPE_ALLOWED
            && $request->input('item.status') === ItemsModel::STATUS_PROGRESS
        ) {
            $itemOnProgress = ItemsModel::where('id_kurir', $sessionToken->getUserId())
                ->where('status', ItemsModel::STATUS_PROGRESS)->first();
            if (!is_null($itemOnProgress)) {
                return $response->errorUnwillingToProcess(trans('you still got item to deliver'));
            }
        }


        $item->name = $request->input('item.name') ? $request->input('item.name') : $item->name;
        $item->receiver_name = $request->input('item.receiver_name') ? $request->input('item.receiver_name') : $item->receiver_name;
        $item->receiver_phone_number = $request->input('item.receiver_phone_number') ? $request->input('item.receiver_phone_number') : $item->receiver_phone_number;
        $item->status = $request->input('item.status') ? $request->input('item.status') : $item->status;
        $item->pickup_address = $request->input('item.pickup_address') ? $request->input('item.pickup_address') : $item->pickup_address;
        $item->destination_address = $request->input('item.destination_address') ? $request->input('item.destination_address') : $item->destination_address;

        if ($sessionToken->getUserType() === CustomerPrivilegeMiddleware::USER_TYPE_ALLOWED) {
            $item->id_customer = $sessionToken->getUserId();
        } else {
            $item->id_customer = $request->input('item.id_customer') ? $request->input('item.id_customer') : $item->id_customer;
        }

        if ($sessionToken->getUserType() === KurirPrivilegeMiddleware::USER_TYPE_ALLOWED) {
            $item->id_kurir = $sessionToken->getUserId();
        } else {
            $item->id_kurir = $request->input('item.id_kurir') ? $request->input('item.id_kurir') : $item->id_kurir;
        }


        if (!$this->runValidation(
            ['item' => $item->toArray()],
            $this->getValidationRules()
        )) {
            return $response->errorInternalError($this->validator->errors()->all());
        }

        $item->save();

        return $response->setStatusCode(Codes::SUCCESS)->withArray([
            'success' => [
                'code' => Codes::SUCCESS,
                'message' => trans('item data successfully updated')
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getValidationRules()
    {
        $rules =  [
            'item.name' => 'required|max:500',
            'item.receiver_name' => 'required|max:150',
            'item.receiver_phone_number' => 'required|max:15',
            'item.pickup_address' => 'required',
            'item.destination_address' => 'required',
            'item.status' => 'in:' . ItemsModel::STATUS_NEW. ',' . ItemsModel::STATUS_PROGRESS . ',' . ItemsModel::STATUS_SENT ,
            'item.id_customer' => 'required|exists:users,id,type,customer',
            'item.id_kurir' => 'required|exists:users,id,type,kurir',
        ];

        return $rules;
    }

    protected function getModelName()
    {
        return \App\Items::class;
    }

    protected function getModelLabel()
    {
        return 'items';
    }

    protected function getTransformer()
    {
        return new \App\Transformer\Items();
    }
}
