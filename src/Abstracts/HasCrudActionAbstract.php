HasCrudAction<?php

namespace SmartPOS\HasCrudAction\Abstracts;

use Illuminate\Http\Request;
use SmartPOS\HasCrudAction\Resolvers\DestroyActionResolver;
use SmartPOS\HasCrudAction\Resolvers\IndexActionResolver;
use SmartPOS\HasCrudAction\Resolvers\ShowActionResolver;
use SmartPOS\HasCrudAction\Resolvers\StoreActionResolver;
use SmartPOS\HasCrudAction\Resolvers\UpdateActionResolver;

abstract class HasCrudActionAbstract
{
    public function index(IndexActionResolver $resolver)
    {
        return $resolver->resolve(new static);
    }

    public function show(ShowActionResolver $resolver, $id)
    {
        return $resolver->resolve(new static, $id);
    }

    public function store(StoreActionResolver $resolver, Request $request)
    {
        return $resolver->resolve(new static, $request->all());
    }

    public function update(UpdateActionResolver $resolver, Request $request, $id)
    {
        return $resolver->resolve(new static, $request->all(), $id);
    }

    public function destroy(DestroyActionResolver $resolver, $id)
    {
        return $resolver->resolve(new static, $id);
    }
}
