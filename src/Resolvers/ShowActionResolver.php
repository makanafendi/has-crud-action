<?php

namespace SmartPOS\HasCrudAction\Resolvers;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use SmartPOS\HasCrudAction\Resolvers\BaseActionResolver;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class ShowActionResolver extends BaseActionResolver
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
            'action' => 'show',
            'id' => $id,
            'route' => Route::getCurrentRoute()->getName(),
        ]);

        if (! method_exists($controller, 'response')) {
            return $controller::$model::findOrFail($id);
        }

        $response = $this->resolveParameters($controller, 'response', array_merge($availableParams, [
            'record' => $controller::$model::findOrFail($id),
        ]));

        return $response;
    }
}
