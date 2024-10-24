<?php

namespace SmartPOS\HasCrudAction\Tests;

use Illuminate\Support\Facades\Route;
use SmartPOS\HasCrudAction\Resolvers\IndexActionResolver;
use Mockery as m;

class IndexActionResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::shouldReceive('getCurrentRequest')
            ->once()
            ->andReturn(new class
            {
                public function method()
                {
                    return 'GET';
                }
            });
        Route::shouldReceive('getCurrentRoute')
            ->once()
            ->andReturn(new class
            {
                public function getName()
                {
                    return 'route.test';
                }
            });

    }

    public function tearDown(): void
    {
        m::close();
    }

    public function test_should_return_all_records_if_response_method_is_not_defined()
    {
        $controller = new class
        {
            public static string $model = 'App\\Model';
        };

        m::mock('alias:App\\Model')
            ->shouldReceive('all')
            ->once()
            ->andReturn(['record1', 'record2']);

        $resolver = new IndexActionResolver();
        $result = $resolver->resolve($controller);

        $this->assertEquals(['record1', 'record2'], $result);
    }

    public function test_should_call_response_method_and_return_its_result()
    {
        $controller = new class
        {
            public static $model = 'App\Model';

            public function response($record)
            {
                return [
                    'data' => $record,
                    'success' => true,
                ];
            }
        };

        m::mock('alias:App\\Model')
            ->shouldReceive('all')
            ->once()
            ->andReturn(['record1', 'record2']);

        $resolver = new IndexActionResolver();
        $result = $resolver->resolve($controller);

        $this->assertEquals([
            'data' => ['record1', 'record2'],
            'success' => true,
        ], $result);
    }
}
