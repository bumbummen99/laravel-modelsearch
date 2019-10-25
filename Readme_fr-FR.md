# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch est un paquet léger et facile à utiliser permettant de créer des requêtes dynamiques pour des modèles spécifiques à Laraval ou Illuminate 5.8.

 # Pré-requis
 - Laravel 5.7+

 # Installation
 ## Composer

 Lancer simplement : ```composer require skyraptor/modelsearch``` pour installer le paquet à sa dernière version. Puis lancer ```composer update```. Le paquet va enregister son propre ServiceProvider en utilisant la découverte de paquet Laravels

## Configuration

Ce paquet inclus son propre fichier de configuration qui vous devez publier avec la command ```php artisan vendor:publish``` en suivant les instructions présente sur votre écran par la suite. Dans ce fichier de configuration vous devez ajuster l'espace de nommage pour vos filtres de dossier et vos préfixes de requetes.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filtre

Afin de définir un filtre vous devez créer un dossier nommé comme votre modèle à l'intérieur de votre dossier de filtre. Dans ce dossier vous pouvez créer un filtre spécifique au modèle.
 Exemple :   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Votre filtre doit étendre ModelSearch\Contracts\Filter.

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


## Le filtre de requête
 Le préfixe du filtre de requête dans la configuration définit le préfixe utilisé pour les noms de filtres dans les paramètres de requête. Ceci peut être utilisé pour permettre à l'utilisateur d'appliquer des filtres aux requêtes POST et GET. Ceci doit être fait manuellement en appelant la méthode ````addRequestFilters``` et en fournissant une instance de requête.

 N'oubliez pas de toujours appliquer les filtres dans l'ordre approprié.

 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Vous pouvez changer le préfixe de filtre de votre rechercher en appelant la méthode ```setRequestFilterPrefix()``` en fournissant un nouveau préfixe. 

## Exemples

L'exemple suivant montre comment utilisé la recherche dans votre contrôleur:

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