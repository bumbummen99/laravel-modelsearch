# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laraver ModelSearch es un paquete ligero y de facil uso para crear consutas dinamicas sobre Modelos usando 
 Laravel o Illuminate 5.8.

 # Requisitos
 - Laravel 5.7+

 # Instalacion
 ## Composer
Ejecuta `composer require skyraptor/modelsearch` para instalar la ultima version del paquete, 
luego ejecuta `composer update`. 
El paquete se registra automaticamente usando el auto descubrimiento de paquetes de Laravel.

## Configuración

 Este paquete incluye su propio archivo de configuracion, el cual deberas publicar ejecutando el comando 
 `php artisan vendor:publish` y luego seguir las intrucciones en la pantalla. 
 En el archivo de configuración tendras que ajustar el namespace de tu directorio de filtros y el prefijo de los filtros 
 en la solicitud.
```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
```

## Filters

 Para definir un filtro, deberas crear una carpeta con el nombre de tu Modelo dentro de la carpeta de filtros.
 Dentro de este directorio puedes crear los filtros espeficios para tu Modelo.
 Por ejemplo:  
 `\app\Filters\[Model:User]\HasId.php`  
 
 Tu filtro debe extender de la clase `ModelSearch\Contracts\Filter`.

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


## Filtros de Peticion
 En la configuración se define el prefijo que que se usara en los parametros de la solicitud.
 Estos pueden ser usados para que el usuario aplique filtros en una peticion POST o GET.
 Esto tiene que hacerse de manera manual llamando al metodo `addRequestFilters` y enviando una instancia de la clase 
 `Request`.
 
 Siempre recuerda aplicar los filtros en el orden correcto.
 
 ```
 $search = new ModelSearch(User::class);
 $search->addRequestFilters($request);
 $result = $search->result();
 ```

Puedes cambiar  el prefijo de los filtros de busqueda luego de llamar `setRequestFilterPrefix()`, y asignando el nuevo 
prefijo.

## Ejemplos

El siguente ejemplo muestra como usar el la Busqueda en tu Controlador:

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
