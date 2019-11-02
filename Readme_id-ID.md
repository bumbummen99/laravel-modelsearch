# Laravel ModelSearch (Laravel ModelPencarian)
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch adalah paket yang ringan dan mudah digunakan untuk membuat permintaan pencarian dinamis untuk Model tertentu dengan Laravel atau Illuminate 5.8.

 # Kebutuhan
 - Laravel 5.7+

 # Instalasi
 ## Composer

 Cukup jalankan ```composer require skyraptor/modelsearch``` untuk menginstal paket dalam versi terbarunya, setelah itu jalankanPaket akan mendaftarkan PenyediaLayanan sendiri menggunakan penemuan paket Laravels.

## Konfigurasi

 Paket ini termasuk file konfigurasinya sendiri yang harus Anda publikasikan dengan perintah ```php artisan vendor:publish``` dan mengikuti instruksi di layar sesudahnya. Dalam file konfigurasi Anda harus menyesuaikan namespace untuk Anda filter direktori dan awalan filter permintaan Anda.

 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filter

 Untuk mendefinisikan filter, Anda harus membuat folder yang dinamai model Anda dalam direktori filter Anda. Dalam folder ini Anda dapat membuat filder khusus untuk model.
 Sebagai contoh:   
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


## Filter Permintaan
 Awalan filter permintaan dalam konfigurasi menentukan awalan yang digunakan untuk nama filter dalam parameter permintaan. Ini dapat digunakan untuk memungkinkan pengguna untuk menerapkan filter melalui permintaan POST dan GET. Ini harus dilakukan secara manual dengan memanggil ```addRequestFilters``` metode dan memberikan contoh Permintaan.

 Selalu ingat untuk menerapkan filter dalam urutan yang sesuai.
 ```
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Anda dapat mengubah awalan filter Pencarian setelah dengan memanggil ```setRequestFilterPrefix()``` metode, memberikan awalan baru.

## Contoh

Contoh berikut menunjukkan kepada Anda bagaimana menggunakan Pencarian di Pengontrol Anda:

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