<?php

namespace ModelSearch\Filters\ExampleModel;

use Illuminate\Database\Eloquent\Builder;
use ModelSearch\Abstracts\Filter;

class HasLabel extends Filter
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
        return $this->builder->where('label_'.$value, true);
    }
}
