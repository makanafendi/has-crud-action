<?php

namespace SmartPOS\HasCrudAction\Resolvers;

use ReflectionClass;

class BaseActionResolver
{
    /**
     * @param  class-string  $controller
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws SuspiciousOperationException
     * @throws ReflectionException
     */
    protected function resolveParameters($controller, string $rules, array $availableParams)
    {
        $controllerReflection = new ReflectionClass($controller);

        $parameters = $controllerReflection->getMethod($rules)->getParameters();
        $arguments = [];
        foreach ($parameters as $parameter) {
            $arguments[] = $availableParams[$parameter->getName()];
        }

        return call_user_func_array([$controller, $rules], $arguments);
    }

    protected function availableKeyParams()
    {
        return [
            'id' => null,
            'method' => null,
            'model' => null,
            'data' => [],
            'record' => null,
            'action' => null,
            'route' => null,
        ];
    }
}
