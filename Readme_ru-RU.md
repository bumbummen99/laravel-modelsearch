# Laravel ModelSearch
![CI](https://github.com/bumbummen99/laravel-modelsearch/workflows/CI/badge.svg)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch это лёгкий, простой в использовании пакет для создания динамических поисковых запросов для специфических моделей с Laravel или Illuminate 5.8.

 # Требования
 - Laravel 5.7+

 # Установка
 ## Composer

 Запустите ```composer require skyraptor/modelsearch``` чтобы установить последнюю версию пакета, после этого выполните ```composer update```. Пакет зарегистрирует собственный ServiceProvider используя Laravel Package Discovery.

## Настройка

 Этот пакет включает собственный конфигурационный файл, который Вам следует опубликовать с помощью команды ```php artisan vendor:publish``` и следуйте инструкциям на экране. В конфигурационном файле вы должны установить namespace для вашей директории с фильтрами и префикс фильтра запроса.

 ```php
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Фильтры

 Для того, чтобы определить фильтр, вы должны создать папку, с таким же названием, как у Вашей модели внутри директории с фильтрами. Внутри этой папки вы можете создать фильтр, специфичный для этой модели.
 Например:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Ваш фильтр должен расширять ModelSearch\Contracts\Filter.

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


## Фильтры запроса
 Префикс фильтра запросов в конфигурации определяет префикс, который используется для фильтра имен в параметрах запроса. Это может быть использовано чтобы позволить пользователю применять фильтры с помощью POST и GET запросов. Это должно быть сделано вручную с помощью вызова метода ```addRequestFilters```, передав ему экземпляр Request.

 Всегда помните о том, что применять фильтры необходимо в a соответствующем порядке.
 ```php
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Вы можете изменить префикс фильтра поиска, вызвав метод ```setRequestFilterPrefix()``` предоставив новый префикс.

## Примеры

Следующие примеры покажут, как использовать поиск в вашем контроллере:

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
