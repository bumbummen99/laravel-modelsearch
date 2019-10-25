# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch é um pacote leve e fácil de usar feito para criar consultas dinâmicas de pesquisas por Modelos específicos com Laravel ou Illuminate 5.8.

 # Requisitos
 - Laravel 5.7+

 # Instalação
 ## Composer

 Apenas rode ```composer require skyraptor/modelsearch``` para instalar o pacote na versão mais recente, e então rode ```composer update```. O pacote irá registrar seu prório ServiceProvider usando a descoberta de pacotes do Laravel.

## Configuração

 Este pacote inclui seu próprio arquivo de configuração que você deve publicar com o comando ```php artisan vendor:publish``` e então seguir as instruções nas telas posteriores. No arquivo de configuração você deve ajustar o namespace para o diretório dos seus filtros e o prefixo da sua requisição.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filtros

 Para poder definir um filtro, você deve criar uma pasta, nomeada como seu modelo, dentro da pasta filters. Dentro da pasta criada você poderá criar filtros específicos para o modelo.
 Por exemplo:
 ```path\to\laravel\app\Filters\User\HasId.php```
 Seu filtro deve extender ModelSearch\Contracts\Filter.

 ```
 <?php

namespace App\Filters\User;

use ModelSearch\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;


class HasId implements Filter
{
    /**
     * Aplique um dado valor de pesquisa à instância builder.
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


## Filtros de Requisições
 O prefixo de filtro de requisição na configuração define o prefixo a ser usado para filtrar nomes nos parâmetros da requisição. Isto pode ser usado para permitir que o usuário aplique filtros através de requisições POST e GET. Isto deve ser feito manualmente chamando o método ```addRequestFilters``` e fornecendo uma instância de requisição.

 Lembre-se sempre de aplicar filtros na ordem apropriada.
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Você pode alterar o prefixo do filtro da Pesquisa posteriormente chamando o método ```setRequestFilterPrefix()``` e fornecendo um novo prefixo.

## Exemplos

O exemplo a seguir mostra como usar a Pesquisa no seu Controller:

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

    // A pesquisa pode ser extendida após o processamento dos resultados
    $search->addFilter('AnotherFilter', 'value');
    $result2 = $search->result();

    ...
}
```