# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

Laravel ModelSearch ist ein kleines, einfach zu verwendendes Paket, welches dynamische Suchen für Modelle mit Laravel oder Illuminate 5.8 ermöglicht.

# Anforderungen
- Laravel 5.7+

# Installation
## Composer

Führe den Befehl ```composer require skyraptor/modelsearch``` aus um das Paket in seiner aktuellsten, kompatiblen Version zu installieren. 
Das Paket wird automatisch über die 'Package Discovery'-Funktion von Laravel geladen.

## Konfiguration

Dieses Paket beinhaltet seine eigene Konfigurationsdatei welche mit dem Befehl ```php artisan vendor:publish``` erzeugt werden kann.
In diesem muss der Request-Filterprefix sowie der Namespace des Ordners angepasst werden, welcher die Filter beinhalten soll.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filter

Um einen Filter zu definieren muss zuerst ein Ordner angelegt werden welcher wie das dazugehörie Modell benamt ist. In diesem Ordner lassen sich Filter zu ihrem dazugehörigen Modell anlegen.
Zum Beispiel:
```path\to\laravel\app\Filters\User\HasId.php```
Alle Filter müssen den Contract ModelSearch\Contracts\Filter implementieren.

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

Der Request-Filterprefix in der Konfigurationsdatei definiert den Prefix welcher für die Benamung der Parameter innerhalb einer Anfrage verwendet wird. 
Diese ermöglichen es dem Benutzer Filter über POST- und GET-Requests zu definieren. 
Um die in der Anfrage definierten Filter zu verwenden muss einmalig die ```addRequestFilters()``` Methode mit einer Request-Instanz als Parameter aufgerufen werden.

Es ist stets zu beachten Filter in der richtigen Reihenfolge hinzuzufügen da sich diese sonst überschreiben könnten.
```
$search = new ModelSearch( User::class );
$search->addRequestFilters( $request );
$result = $search->result();
```

Der Request-Filterprefix lässt sich auch zur Laufzeit über die ```setRequestFilterPrefix()``` Methode, mit dem neuen Präfix als Parameter, anpassen.

## Beispiele

Das folgende Beispiel zeigt die Verwendung der Suche in einem Controller:

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
