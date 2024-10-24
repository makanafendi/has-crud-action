## Features

* **CRUD** - easily create, read, update, and delete data entities.
* **Rules** - you can add the rules for your CRUD actions on the fly.
* **Magic Parameters** - magic parameters that can be used in your actions.

## Getting Started

### Installation

You can install the package via composer:

```bash
composer require smartpos/has-crud-action
```

### Usage

```php
// in your routes file
Route::resource('suppliers', SupplierController::class);

// in your controller file
use SmartPOS\HasCrudAction\Abstracts\HasCrudActionAbstract;
use App\Models\Supplier;

class SupplierController extends HasCrudActionAbstract
{
    public static string $model = Supplier::class;
}
```

### Methods
`rules` the rules method allows you to add your own rules to the action.
```php
public static function rules(): array
{
    return [
        'phone_number' => 'unique:suppliers,phone_number',
        'name' => 'required'
    ];
}
```

`beforeStore` the beforeStore method allows you to modify the data before it is stored.
```php
public static function beforeStore($data, $model): Model
{
    $model->name = strtoupper($data['name']);

    return $model;
}
```

`beforeUpdate` the beforeUpdate method allows you to modify the data before it is updated.
```php
public static function beforeUpdate($data, $model): Model
{
    $model->name = strtoupper($data['name']);

    return $model;
}
```

`beforeDestroy` the beforeDestroy method allows you to modify the data before it is destroyed.
```php
public static function beforeDestroy($model): Model
{
    dd($model);

    return $model;
}
```

`response` the response method allows you to modify the response data.
```php
public static function response($record)
{
    return [
        'data' => $record,
        'success' => true,
    ];
}
```

### Magic Parameters

* $id - The ID of the record
* $method - The HTTP method (GET, POST, PUT, PATCH, DELETE)
* $model - The model class name
* $data - The data sent from the request
* $record - The record object associated with the action
* $action - The current method action name
* $route - The current route name


### Testing

```bash
composer test
```

### Todo

* [ ] Pagination support
* [ ] Filter support
* [ ] ModifyQuery
* [ ] Error handler for unsupported magic parameter
* [ ] Relation support
* [ ] Unit test
* [ ] Pipeline

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email makanafendi@gmail.com instead of using the issue tracker.

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

### Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
