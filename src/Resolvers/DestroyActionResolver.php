<?php

namespace SmartPOS\HasCrudAction\Resolvers;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use SmartPOS\HasCrudAction\Resolvers\BaseActionResolver;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class DestroyActionResolver extends BaseActionResolver
{
    /**
     * @param  class-string  $controller
     * @param  mixed  $id
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws SuspiciousOperationException
     */
    public function resolve($controller, $id)
    {
        $availableParams = array_merge($this->availableKeyParams(), [
            'method' => Route::getCurrentRequest()->method(),
            'model' => new $controller::$model(),
            'action' => 'update',
            'id' => $id,
            'route' => Route::getCurrentRoute()->getName(),
        ]);

        $model = $controller::$model::find($id);
        if (method_exists($controller, 'beforeDestroy')) {
            $model = $this->resolveParameters($controller, 'beforeDestroy', array_merge($availableParams, [
                'model' => $model,
                'record' => $model,
            ]));
        }
        $model->delete();

        if (! method_exists($controller, 'response')) {
            return $model;
        }

        $response = $this->resolveParameters($controller, 'response', $availableParams);

        return $response;
    }
}
