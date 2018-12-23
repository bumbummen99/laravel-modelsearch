<?php

namespace ModelSearch\Filters\ExampleModel;

use ModelSearch\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;


class SortBy implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $sortType)
    {
        switch ($sortType)
        {
            case 'id':
                return $builder->orderBy('id');
            case 'idDesc':
                return $builder->orderBy('id', 'desc');
            default:
                return $builder;
        }
    }
}