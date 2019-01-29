# Nova Resource Status 
A laravel Nova package which allows you to track the status of your Nova resource.

## Installation

You can install the package via composer:

```bash
composer require digitalcloud/nova-resource-status
```

You must publish the migrations file:

```shell
php artisan vendor:publish --provider="DigitalCloud\NovaResourceStatus\ToolServiceProvider" --tag=migrations
```

Then, migrate the database table:

```shell
php artisan migrate
```

Optionally, you can publish the config file:

```shell
php artisan vendor:publish --provider="DigitalCloud\NovaResourceStatus\ToolServiceProvider" --tag=config
```

This is the contents of the file which will be published at `config/nova-resource-status.php`

```php
<?php

return [
    /*
     * The class name of the status model that holds all statuses.
     * The model must be or extend `DigitalCloud\NovaResourceStatus\Models\Status`.
     */
    'status_model' => DigitalCloud\NovaResourceStatus\Models\Status::class,

    /*
     * The default name of the status attribute if not declared in model.
     */
    'status-field' => 'status'
];
```

## Usage

You must add `HasStatus` trait to the resource model.

```php
use DigitalCloud\NovaResourceStatus\HasStatus;

class YourEloquentModel extends Model
{
    use HasStatus;
    
    // use this function to indicate the status column in this model.
    // If this function not existed, then the value of `status-field`
    // form config file will be considered.
    public function statusField() {
        return 'yourStatusColumn';
    }
}
```

This will add database record each time the status attribute changes.

To show all status log, add the `Statuses` field in your nova resource:

```php
<?php

namespace App\Nova;

use DigitalCloud\NovaResourceStatus\Fields\Statuses;
use Illuminate\Http\Request;

class YourResource extends Resource {
    
    // ...
    
    public static $model = 'YourEloquentModel'; // model must use `HasStatus` trait`
    
    public function fields(Request $request)
    {
        return [
            // ...
            // This will appear in the resource detail view.
            Statuses::make(),
            // ...
        ];
    }
    
    // ...

}
```

Then, in resource detail page, you can see the history of your resource status.

## Images
![status](https://user-images.githubusercontent.com/41853913/51898326-598f5b80-23b9-11e9-89b0-7045ddba7941.PNG)
