<?php

namespace SmartPOS\HasCrudAction\Tests;

use Illuminate\Support\Facades\Route;
use SmartPOS\HasCrudAction\Resolvers\ShowActionResolver;
use Mockery as m;

class ShowActionResolverTest extends TestCase
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

    public function test_should_return_record_if_response_method_is_not_defined()
    {
        $controller = new class
        {
            public static string $model = 'App\\Model';
        };

        m::mock('alias:App\\Model')
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn('record1');

        $resolver = new ShowActionResolver();
        $result = $resolver->resolve($controller, 1);

        $this->assertEquals('record1', $result);
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
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn('record1');

        $resolver = new ShowActionResolver();
        $result = $resolver->resolve($controller, 1);

        $this->assertEquals([
            'data' => 'record1',
            'success' => true,
        ], $result);
    }
}
