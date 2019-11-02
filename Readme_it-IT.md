# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch é una semplice libreria per realizzare query di ricerca su specifici Modelli con Laravel o Illuminate 5.8

 # Requisiti
 - Laravel 5.7+

 # Installazione
 ## Composer

 Esegui il comando ```composer require skyraptor/modelsearch``` per installare l'ultima versione della libreria. A seguire esegui ```composer update```. La libreria registrerá il proprio ServiceProvider usando il gestore pacchetti Laravel.

## Configurazione

 Il pacchetto include un file di configurazione che dovresti pubblicare con il comando ```php artisan vendor:publish``` e se guendo le istruzione della seguente schermata. Nel file di configurazione devi modificare il namespace relativo alla tua directory dei filtri ed il prefisso della tua filter request.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filtri

 Per definire un filtro devi creare un cartella chiamata come il tuo modello all'interno della tua cartella dei filtri. Dentro questa cartella puoi creare un finder specifico per il modello.
 Ad esempio:   
  ```path\to\laravel\app\Filters\User\HasId.php```
 Il tuo filtro deve estendere ModelSearch\Contracts\Filter.

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
 Il prefisso request filter nel file di configurazione definisce il prefisso da usare per i nomi dei filtri nei parametri della request. Ció puó essere usato per permettere all'utente di applicare filtri nelle request POST e GET. Andrá fatto manualmente chiamando il metodo ```addRequestFilters``` e passandogli un'istanza della request.

 Ricorda sempre di applicare i filtri nell'ordine corretto.
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Puoi cambiare il prefisso dei filtri di ricerca successivamente chiamando il metodo ```setRequestFilterPrefix()```, fornendo un nuovo prefisso.

## Esempi

L'esempio seguente mostra come usare la Search nel Controller:

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
