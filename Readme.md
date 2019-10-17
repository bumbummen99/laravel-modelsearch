# Laravel ModelSearch
[![Status construit](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total téléchargé](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Derniere Version Stable](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Derniere Version Instable](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Page d'Accueil](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch est légé, et possède des paquet facile à utiliser, pour créer des requetes dynamique de recherches pour des modèles spécifique avec LAravel or Illuminate
 5.8. 
 # Prérequis
 - Laravel 5.7+

 # Installation
 ## Composer

 Lancement facile ```composer require skyraptor/modelsearch``` pour installer le paquet dans sa dernière version, apères cela lancer```composer update```. Le package enregistrera son propre fournisseur de services à l'aide du gestionnaire de paquet Laravels.

## Configuration

 Ce paquet inclut son propre fichier de configuration que vous devez publier avec la commande ```php artisan vendor:publish``` et en suivant les instructions à l’écran. Dans le fichier de configuration, vous devez ajuster l'espace de noms pour votre répertoire de filtres et votre préfixe de filtre de requête votre requête..

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filtres

Afin de définir un filtre, vous devez créer un dossier basé sur le modèle de votre filtre dans votre repertoire. Dans ce dossier, vous pouvez créer un fichier spécifique au modèle.
 Par exemple:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Votre filtre doit se baser sur ModelSearch\Contracts\Filter.

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


## Filtre De Demande
 Le préfixe de filtre de demande dans la configuration définit le préfixe utilisé pour les noms de filtre dans les paramètres de demande. Ceci peut être utilisé pour permettre à l'utilisateur d'appliquer des filtres via des requêtes POST et GET. Ceci doit être fait manuellement en appelant le ```addRequestFilters``` méthode et fournissant une instance de demande.

 Rappelez-vous toujours d'appliquer les filtres dans l'ordre approprié.
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Vous pouvez modifier le préfixe de filtre de Search après en appelant la méthode `` `setRequestFilterPrefix ()` ``, en fournissant un nouveau préfixe.

## Examples

L'exemple suivant vous montre comment utiliser la recherche dans votre contrôleur:

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