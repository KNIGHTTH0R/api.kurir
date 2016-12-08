<?php

namespace App\Http\Controllers;

use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    public function index(Response $response, Request $request)
    {
        if ($request->filter) {
            return $this->filter($response, $request);
        }

        if ($this->usePaginationByDefault() || $request->page) {
            return $this->paging($response);
        }

        $models = call_user_func($this->getModelName() . '::all');

        if (empty($models) || count($models) === 0) {
            return $response->errorNotFound([
                trans(
                    'errors.data_empty',
                    [
                        'dataname' => $this->getModelLabel()
                    ]
                )
            ]);
        }

        return $response->withCollection(
            $models,
            $this->getListTransformer(),
            null,
            null,
            [
                'total' => count($models)
            ]
        );
    }

    public function show(Response $response, $id)
    {
        $models = call_user_func_array($this->getModelName() . '::find', [$id]);
        if(empty($models) || count($models) === 0){
            return $response->errorNotFound([
                trans(
                    'errors.data_not_found',
                    [
                        'dataname' => $this->getModelLabel()
                    ]
                )
            ]);
        }

        return $response->withItem($models, $this->getTransformer());
    }

    protected function filter(Response $response, Request $request)
    {
        if (!preg_match('/^[a-zA-Z0-9_]+:[a-zA-Z0-9_]+$/', $request->filter)) {
            return $response->errorWrongArgs('wrong filter arg, must be "?filter=type:value"');
        }

        $filter = explode(':', $request->filter);
        $filter_type = $filter[0];
        $filter_value = $filter[1];

        try {
            $models = call_user_func_array(
                $this->getModelName() . '::' . $filter_type,
                [$filter_value]
            )->paginate($this->getPerPage());
        } catch (\BadMethodCallException $e) {
            return $response->errorWrongArgs('wrong filter arg, invalid filter type ' . $filter_type);
        }

        if (empty($models) || count($models) === 0) {
            return $response->errorNotFound([
                trans(
                    'errors.data_empty',
                    [
                        'dataname' => $this->getModelLabel()
                    ]
                )
            ]);
        }

        return $response->withPaginator($models, $this->getListTransformer());
    }

    protected function paging(Response $response)
    {
        $models = call_user_func_array(
            $this->getModelName() . '::paginate',
            [
                $this->getPerPage()
            ]
        );

        if (empty($models) || count($models) === 0) {
            return $response->errorNotFound([
                trans(
                    'errors.data_empty',
                    [
                        'dataname' => $this->getModelLabel()
                    ]
                )
            ]);
        }

        return $response->withPaginator($models, $this->getListTransformer());
    }

    protected function getPerPage()
    {
        return 10;
    }

    protected function usePaginationByDefault()
    {
        return true;
    }

    protected function getListTransformer()
    {
        return $this->getTransformer();
    }

    abstract protected function getModelName();

    abstract protected function getModelLabel();

    /**
     * @return \League\Fractal\TransformerAbstract
     */
    abstract protected function getTransformer();
}