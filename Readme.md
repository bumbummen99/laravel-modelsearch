# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch is a lightweight, easy to use package to create dynamic search queries for specific Models with Laravel or Illuminate 5.8.

 # Requirements
 - Laravel 5.7+

 # Installation
 ## Composer

 Simply run ```composer require skyraptor/modelsearch``` to install the package in its latest version, after that run ```composer update```. The package will register its own ServiceProvider using Laravels package discovery.

## Configuration

 This package includes its own configuration file which you should publish by with the command ```php artisan vendor:publish``` and following the instuctions on screenafterwards. In the configuration file you have to adjust the namespace for you filters directory and your request filter prefix.

 ```php
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filters

 In order to define a filter you have to create a folder that is named as your model within your filters directory. Within this folder you can create filter specific to the model.
 For example:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Your filter has to extend ModelSearch\Contracts\Filter.

 ```php
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
 ```php
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

You can change the filter prefix of the Search after by calling the ```setRequestFilterPrefix()``` method, providing a new preifx.

## Examples

The following example shows you how to use the Search in your Controller:

```php
namespace ModelSearch\ModelSearch;


public function someController( Request $request ) {
    ...

    $search = new ModelSearch( User::class );
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
