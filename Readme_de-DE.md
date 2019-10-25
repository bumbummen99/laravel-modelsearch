# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch ist eine leichtgewichtige, einfach zu benutzende Bibliothek, die dynamische Queries, zur Suche von spezifischen Modellen, in Laravel oder Illuminate 5.8 zur Verfügung stellt.

 # Vorraussetzungen
 - Laravel 5.7+

 # Installation
 ## Composer

 Führe ```composer require skyraptor/modelsearch``` aus, um die Bibliothek in der neuesten Version zu installieren. Führe anschließend ```composer update``` aus. Die Bibliothek registitriert mithilfe der Laravel Bibliotheks Erkennung, seinen eigenen ServiceProvider.

## Konfiguration

 Die Bibliothek enthält eine eigene Konfigurations-Datei, welche mit dem Befehl ```php artisan vendor:publish``` und dem Ausführen der darauffolgenden, auf dem Bildschirm angezeigten Instruktionen, veröffentlicht wird. Um den namespace für deine filter Ordner und den Prefix für deine Request filter anzupassen, musst du die Konfigurationsdatei anpassen.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filter

 Um einen Filter zu definieren, erstelle einen neuen Ordner im Filter Ordner, der den gleichen Namen, wie dein Model besitzt. In diesem Ordner kannst du spezifische Filter für dein Model erstellen.
 Zum Beispiel:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Dein Filter muss ModelSearch\Contracts\Filter erweitern. 

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


## Request Filter
 Der request filter prefix in der Konfiguration, definiert den Prefix, welcher für die Filternamen in den Request Parametern benutzt wird. So kann man Filter durch POST und GET Requests anwenden. Dies muss manuell erledigt werden, indem die Methode ```addRequestFilters``` mit einer Request-Instanz aufgerufen wird.

Vergiss nicht, die Filter in der richtigen Reihenfolge anzuwenden
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Der Filterprefix der Suche wird geändert, indem die Methode ```setRequestFilterPrefix()``` mit einem neuen Prefix aufgerufen wird.

## Beispiele

Die folgenden Beispiele zeigen, wie die Suche im Controller verwendet wird:

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
