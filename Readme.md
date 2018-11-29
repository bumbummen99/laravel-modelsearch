# Laravel ModelSearch

 Laravel ModelSearch is a lightweight, easy to use package to create dynamic search queries for specific Models with Laravel and Illuminate.

 # Requirements
 - Laravel 5.7+

 # Installation
 ## Composer

 Simply run ```composer require skyraptor/modelsearch``` to install the package in its latest version, after that run ```composer update```. The package will register its own ServiceProvider using Laravels package discovery.

## Configuration

 This package includes its own configuration file which you should publish by with the command ```php artisan vendor:publish``` and following the instuctions on screenafterwards. In the configuration file you have to adjust the namespace for you filters directory and your request filter prefix.

 ```
return [
    'filtersFQDN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filters

 In order to define a filter you have to create a folder that is named as your model within your filters directory. Within this folder you can create filder specific to the model.
 For example:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Your filter has to extend ModelSearch\Contracts\Filter.

 ```
 <?php

namespace App\Filters\User;

use ModelSearch\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;


class HasId implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param integer $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->where( 'id', $value );
    }
}
 ```


## Request Filters
 The request filter prefix  in the configuration defines the prefix being used for filter names in the request parameters. This can be used to allow the user to apply filters trought POST and GET requests. This has to be done manually by calling the ```addRequestFilters``` method and providing a Request instance.

 Always remember to apply filters in the appropiate order.
 ```
 $search = new ModelSearch( new User() );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

You can change the filter prefix of the Search after by calling the ```setRequestFilterPrefix()``` method, providing a new preifx.

## Examples

The following example shows you how to use the Search in your Controller:

```
namespace ModelSearch\ModelSearch;


public function someController( Request $request ) {
    ...

    $search = new ModelSearch( new User() );
    $search->addFilters([
        'HasId' => 1,
        'HasLastName' => 'Doe'
    ]);
    $search->addFilter('SomeFilter', 'value');
    $result = $search->result();

    // The search can be extended after processing results
    $search->addFilter('AnotherFilter', 'value');
    $result2 = $search->result();

    ...
}
```