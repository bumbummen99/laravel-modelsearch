<?php

namespace ModelSearch\Filters\ExampleModel;

use ModelSearch\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;


class HasName implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->where( 'name', $value );
    }
}
