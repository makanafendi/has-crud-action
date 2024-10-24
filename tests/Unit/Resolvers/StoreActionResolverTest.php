<?php

namespace SmartPOS\HasCrudAction\Tests\Unit\Resolvers;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use SmartPOS\HasCrudAction\Resolvers\StoreActionResolver;
use SmartPOS\HasCrudAction\Tests\TestCase;
use Mockery as m;

class StoreActionResolverTest extends TestCase
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

    public function test_store_method_with_rules_validation()
    {
        $controller = new class
        {
            public static string $model = 'App\\Model';

            public function rules(): array
            {
                return [
                    'name' => 'required',
                    'email' => 'required|email',
                ];
            }
        };
        m::mock('alias:App\\Model');

        $this->expectException(ValidationException::class);

        $resolver = new StoreActionResolver();
        $resolver->resolve($controller, []);
    }

    public function test_store_method_with_beforeStore_method()
    {
    }

    public function test_should_return_record_if_response_method_is_not_defined()
    {
    }

    public function test_should_call_response_method_and_return_its_result()
    {
        $controller = new class
        {
            public static string $model = 'App\\Model';
        };

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $mockModel = m::mock('alias:App\\Model');
        $mockModel->shouldReceive('fill')
            ->once()
            ->with($data)
            ->andReturn(m::self());
        $controller::$model = get_class($mockModel);

        $mockModel->shouldReceive('save')
            ->once()
            ->andReturn((object) ['name' => 'John Doe', 'email' => 'john@example.com']);

        m::mock('alias:App\\Model')
            ->shouldReceive('new')
            ->andReturn($mockModel);

        $resolver = new StoreActionResolver();

        $result = $resolver->resolve($controller, $data);

        $this->assertInstanceOf('App\\Model', $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
    }
}
