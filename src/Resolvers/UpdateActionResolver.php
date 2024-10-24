<?php

namespace SmartPOS\HasCrudAction\Resolvers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class UpdateActionResolver extends BaseActionResolver
{
    /**
     * @param  class-string  $controller
     * @param  mixed  $data
     * @param  mixed  $id
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws SuspiciousOperationException
     * @throws ValidationException
     */
    public function resolve($controller, $data, $id)
    {
        $availableParams = array_merge($this->availableKeyParams(), [
            'method' => Route::getCurrentRequest()->method(),
            'model' => new $controller::$model(),
            'action' => 'update',
            'id' => $id,
            'data' => $data,
            'route' => Route::getCurrentRoute()->getName(),
        ]);

        if (method_exists($controller, 'rules')) {
            $rules = $this->resolveParameters($controller, 'rules', $availableParams);

            Validator::make($data, $rules)->validate();
        }

        $model = $controller::$model::findOrFail($id);
        $model->fill($data);
        if (method_exists($controller, 'beforeUpdate')) {
            $model = $this->resolveParameters($controller, 'beforeUpdate', array_merge($availableParams, [
                'model' => $model,
            ]));
        }
        $model->save();

        if (! method_exists($controller, 'response')) {
            return $model;
        }

        $response = $this->resolveParameters($controller, 'response', array_merge($availableParams, [
            'model' => $model,
            'data' => $data,
            'record' => $model,
        ]));

        return $response;
    }
}
