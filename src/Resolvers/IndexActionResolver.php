<?php

namespace SmartPOS\HasCrudAction\Resolvers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use SmartPOS\HasCrudAction\Interfaces\WithPagination;
use SmartPOS\HasCrudAction\Interfaces\WithSimplePagination;
use ReflectionException;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class IndexActionResolver extends BaseActionResolver
{
    /**
     * @param  class-string  $controller
     * @return array
     *
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws SuspiciousOperationException
     * @throws ReflectionException
     */
    public function resolve($controller)
    {
        $model = app($controller::$model);
        $availableParams = array_merge($this->availableKeyParams(), [
            'method' => Route::getCurrentRequest()->method(),
            'model' => $model,
            'action' => 'index',
            'route' => Route::getCurrentRoute()->getName(),
            'input' => Request::input(),
        ]);

        $usingPagintation = false;
        if ($controller instanceof WithPagination) {
            $usingPagintation = true;
        }

        $simplePagination = false;
        if ($controller instanceof WithSimplePagination) {
            $simplePagination = true;
        }

        if (method_exists($controller, 'filter')) {
            $model = QueryBuilder::for($model)
                ->allowedFilters($this->resolveParameters($controller, 'filter', $availableParams));
        }

        if (! method_exists($controller, 'response')) {
            if ($simplePagination) {
                return $model->simplePaginate();
            }

            return $usingPagintation ? $model->paginate() : $model->get();
        }

        $record = $usingPagintation ? $model->paginate() : $model->get();
        if ($simplePagination) {
            $record = $model->simplePaginate();
        }

        $response = $this->resolveParameters($controller, 'response', array_merge($availableParams, [
            'record' => $record,
        ]));

        return $response;
    }
}
