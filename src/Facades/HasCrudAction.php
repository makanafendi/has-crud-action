<?php

namespace SmartPOS\HasCrudAction\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SmartPOS\HasCrudAction\Skeleton\SkeletonClass
 */
class HasCrudAction extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'has-crud-action';
    }
}
