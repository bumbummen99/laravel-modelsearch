<?php

namespace ModelSearch\Filters\ExampleModel;

use Illuminate\Database\Eloquent\Builder;
use ModelSearch\Abstracts\Filter;

class SortBy extends Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param mixed $value
     *
     * @return Builder $builder
     */
    public function apply($value)
    {
        switch ($value) {
            case 'id':
                return $this->builder->orderBy('id');
            case 'idDesc':
                return $this->builder->orderBy('id', 'desc');
            default:
                return $this->builder;
        }
    }
}
