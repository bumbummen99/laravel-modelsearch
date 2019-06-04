<?php

namespace ModelSearch\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Run the Filter instance on the Builder instance.
     *
     * @param Illuminate\Database\Eloquent\Builder $builder
     *
     * @return Illuminate\Database\Eloquent\Builder $builder
     */
    public function run(Builder $builder);

    /**
     * Apply a given search value to the Builder instance.
     *
     * @param Illuminate\Database\Eloquent\Builder $builder
     *
     * @return Illuminate\Database\Eloquent\Builder $builder
     */
    public function apply($value);

    /**
     * Add a value to the Filter instance.
     * Multiple values will be run in sequence.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function addValue($value);

    /**
     * Remove a value from the Filter instance.
     * Multiple values will be run in sequence.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function removeValue($value);
}
