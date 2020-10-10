# Laravel ModelSearch
[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

 Laravel ModelSearch là một gói nhỏ, dễ sử dụng để tạo các quy vấn tìm kiếm động cho các Models của Laravel hoặc Illuminate 5.8.

 # Requirements
 - Laravel 5.7+

 # Installation
 ## Composer

 Chỉ cần chạy ```composer require skyraptor/modelsearch```để cài đặt package của nó với phiên bản mới nhất, sau đó chạy ```composer update```. Gói sẽ đăng ký tại ServiceProvider sử dụng Laravels package khám phá.

## Configuration

 Gói này bao gồm tệp cấu hình của riêng nó mà bạn nên xuất bản bằng command ```php artisan vendor:publish``` xuất bản và theo dõi các phiên bản trên màn hình sau. rong tệp cấu hình, bạn phải điều chỉnh không gian tên cho thư mục bộ lọc và tiền tố bộ lọc yêu cầu của bạn.

 ```php
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

## Filters


 Để xác định filter, bạn phải tạo một thư mục được đặt tên là model của bạn trong thư mục filters của bạn. Trong thư mục này, bạn có thể tạo filter cụ thể cho model.
 Ví dụ:   
 ```path\to\laravel\app\Filters\User\HasId.php```
 Your filter has to extend ModelSearch\Contracts\Filter.

 ```php
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
 Tiền tố filter yêu cầu trong cấu hình xác định tiền tố được sử dụng cho tên bộ lọc trong các tham số yêu cầu. Điều này có thể được sử dụng để cho phép người dùng áp dụng các bộ lọc theo yêu cầu POST và GET. Điều này phải được thực hiện theo cách thủ công bằng cách gọi phương thức ```addRequestFilters``` và cung cấp một method Yêu cầu.

 Luôn nhớ áp dụng các filters theo thứ tự phù hợp.
 ```php
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

Bạn có thể thay đổi tiền tố bộ lọc của Tìm kiếm sau bằng cách gọi ```setRequestFilterPrefix()``` phương thức, cung cấp một tiền tố mới.

## Examples

Ví dụ sau đây cho bạn thấy cách sử dụng Tìm kiếm trong Controller:

```php
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
