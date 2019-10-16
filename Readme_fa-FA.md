<div dir="rtl" style="direction:rtl;text-align:right;">
<h1>جستجوی مدل لاراول</h1>
</div>

[![Build Status](https://travis-ci.org/bumbummen99/laravel-modelsearch.png?branch=master)](https://travis-ci.org/bumbummen99/laravel-modelsearch)
[![codecov](https://codecov.io/gh/bumbummen99/laravel-modelsearch/branch/master/graph/badge.svg)](https://codecov.io/gh/bumbummen99/laravel-modelsearch)
[![StyleCI](https://styleci.io/repos/159666547/shield?branch=master)](https://styleci.io/repos/159666547)
[![Total Downloads](https://poser.pugx.org/skyraptor/modelsearch/downloads.png)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Stable Version](https://poser.pugx.org/skyraptor/modelsearch/v/stable)](https://packagist.org/packages/skyraptor/modelsearch)
[![Latest Unstable Version](https://poser.pugx.org/skyraptor/modelsearch/v/unstable)](https://packagist.org/packages/skyraptor/modelsearch)
[![License](https://poser.pugx.org/skyraptor/modelsearch/license)](https://packagist.org/packages/skyraptor/modelsearch)
[![Homepage](https://img.shields.io/badge/homepage-skyraptor.eu-informational.svg?style=flat&logo=appveyor)](https://skyraptor.eu)

<div dir="rtl" style="direction:rtl;text-align:right;">
<p>
 Laravel Model Search یک پکیج سبک و با کاربرد آسان است تا بتوانید پرس و جوهای جستجوی پویا را برای مدلهای خاص با Laravel یا Illuminate 5.8 ایجاد کنید.
</p>
</div>

<div dir="rtl" style="direction:rtl;text-align:right;">
 <h1>ابزار های وابسته</h1>

- لاراول 5.7+

</div>
<div dir="rtl" style="direction:rtl;text-align:right;">
 <h1> نصب کردن</h1>
 <h2> کامپوزر</h2>

<p>
دستور کامپوزر  ```composer require skyraptor/modelsearch``` را برای نصب ساده اجرا کنید تا آخرین نسخه موجود نصب شود و سپس برای بروزرسانی دستور زیر را اجرا کنید.
```composer update```

این بسته با استفاده از ServiceProvider بسته Laravels ، ServiceProvider خود را ثبت می کند.
</p>
</div>
<div dir="rtl" style="direction:rtl;text-align:right;">
<h2>تنظیم و کانفیگ</h2>
<p>
این بسته شامل پرونده پیکربندی خود می باشد که شما باید با دستور ```php artisan vendor:publish``` و دنبال کردن نمونه های موجود در screenafterward. در فایل پیکربندی باید فضای نام را برای فهرست فیلترها و پیشوند فیلتر درخواست خود تنظیم کنید.
 </p>
 </div>
 ```
return [
    'filtersFQCN' => 'App\\Filters\\',
    'requestFilterPrefix' => 'filter_'
];
 ```

<div dir="rtl" style="direction:rtl;text-align:right;">
<h2>فیلتر ها</h2>
<p>
برای تعریف یک فیلتر ، باید پوشه ای را ایجاد کنید که به عنوان مدل خود در فهرست پوشه فیلترهای شما قرار گیرد. در این پوشه می توانید پوشه مخصوص مدل را ایجاد کنید. مثلا:

مثلا:

</p>
</div>
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

<div dir="rtl" style="direction:rtl;text-align:right;">
<h2>درخواست فیلتر</h2>

<p>
The request filter prefix  in the configuration defines the prefix being used for filter names in the request parameters. This can be used to allow the user to apply filters trought POST and GET requests. This has to be done manually by calling the ```addRequestFilters``` method and providing a Request instance.

همیشه به یاد داشته باشید که فیلترها را به ترتیب مناسب اعمال کنید.
</p>
</div>

```php
 $search = new ModelSearch( User::class );
 $search->addRequestFilters( $request );
 $result = $search->result();
 ```

<div dir="rtl" style="direction:rtl;text-align:right;">
<p dir="rtl">
می توانید پیشوند فیلتر جستجو را با فراخوانی متد  ```setRequestFilterPrefix ()```تغییر دهید و یک پیشوند جدید ارائه دهید.
</p>
<h2>مثال ها</h2>

<p dir="rtl">
مثال زیر نحوه استفاده از جستجو در Controller را به شما نشان می دهد:
</p>
</div>


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
