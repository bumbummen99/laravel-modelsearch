# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)


 Laravel ModelSearch to lekka, prosta w użyciu paczka do tworzenia dynamicznych zapytań dla konkretnych Modeli w Laravel lub Illuminate 5.8.

 # Wymagania
 - Laravel 5.7+

 # Instalacja
 ## Composer

 Uruchom ```composer require skyraptor/modelsearch``` aby zainstalować najnowszą wersję paczki, następnie uruchom ```composer update```. Paczka zarejestruje własny ServiceProvider przy uzyciu Laraver Package Discovery

## Konfiguracja


 Paczka zawiera własny plik konfiguracyjny który powinieneś zainicjować poleceniem  ```php artisan vendor:publish``` a następnie postępując zgodnie z poleceniami. W pliku konfiguracyjnym musisz zmienić namespace dla katalogu filtrów oraz prefix filtrów.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filtry

 Aby zdefiniować filtr musisz stworzyć folder o takiej samej nazwie jak model wewnątrz folderu z filtrami. Wewnątrz tego folderu, możesz stworzyć filtr dedykowany dla tego modelu.

 Na przykład:   
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


## Filtr Request

 Prefiks filtra w pliku konfiguracyjnym definiuje jaki prefiks będzie użyty to filtrowania nazw w parametrze żądania. Można tego użyć aby zezwolić użytkownikowi do zaaplikowania filtru poprzez żądania POST i GET. To musi zostać zrobione manualnie poprzez wywołanie metody ```addRequestFilters``` oraz podanie instancji Reguest

 Pamiętaj aby zawsze aplikować filtry w odpowiedniej kolejności:
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Możesz zmienić prefiks filtra zapytania poprzez wywołanie metody ```setRequestFilterPrefix()``` podając nowy prefiks

## Przykłady

Poniższy przykład pokazuje jak użyć Zapytania w kontrolerze:

```
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
