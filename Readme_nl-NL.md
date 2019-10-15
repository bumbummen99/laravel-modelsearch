# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch is een licht gewicht, en makkelijk te gebruiken package om dynamische search queries te maken voor specieke Models met Laraval of Illuminate 5.8

 # Vereisde
 - Laravel 5.7+

 # Installatie
 ## Composer

 Run ```composer require skyraptor/modelsearch``` om de laatste versie van de package te installeren, run daarna ```composer update```. De package zal zijn eigen ServiceProvider registeren door gebruik van Laravels package discovery.

## Configuratie

 De package heeft zijn eigen configuratie bestand welke je moet publiseren met de command  ```php artisan vendor:publish``` en volg de instructie's op het scherm. In het configuratie bestand moet je de namespace aanpassen voor jou filter locatie en voor jou filter verzoek prefix.



 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filters

 Om je filters te defineren moet je een folder aanmaken die vernoemd is naar de model van je filter locatie. In deze folder kan je een filter maken speciek voor de model.
 Voorbeeld:

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
     * Geef een Search waarden aan de builder instance.
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


## Verzoek Filters
 De filter verzoeken prefix in de configuratie bepaald welke prefix word gebruikt voor de filter namen in de request parameter. Dit kan gebruikt worden om gebruikers filters te laten gebruiken doormiddel van POST en GET requests. Dit moet handmatig gebeuren door ```addRequestFilters``` method aan te roepen en voorzien van een Request instance.

 Onthoud dat je de filters in de juiste volg order toepast.
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Je kan de fitler prefix aanpassen door de ```setRequestFilterPrefix()``` method aan te roepen, met een nieuwe preifx.

## Voorbeelden

Het volgende voorbeeld laat zien hoe je de Search moet gebruiken in je Controller:

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
