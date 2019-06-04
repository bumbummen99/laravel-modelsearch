<?php

namespace ModelSearch\Abstracts;

use Illuminate\Database\Eloquent\Builder;
use ModelSearch\Contracts\Filter as FilterContract;

abstract class Filter implements FilterContract
{
    /**
     * Contains the values for the Filter instance.
     *
     * @var array
     */
    private $values = [];

    /**
     * Contains the builder instance for the current run.
     *
     * @var array
     */
    protected $builder;

    /**
     * Create a new Filter instance.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        array_push($this->values, $value);
    }

    /**
     * Apply a given search value to the Builder instance.
     *
     * @param Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                $value
     *
     * @return Illuminate\Database\Eloquent\Builder $builder
     */
    public function run(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->values as $value) {
            $this->builder = $this->apply($value);
        }

        return $this->builder;
    }

    /**
     * Apply a given search value to the Builder instance.
     *
     * @param Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                $value
     *
     * @return Illuminate\Database\Eloquent\Builder $builder
     */
    abstract public function apply($value);

    /**
     * Add a value to the Filter instance.
     * Multiple values will be run in sequence.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function addValue($value)
    {
        if (!$this->hasValue($value)) {
            array_push($this->values, $value);
        }
    }

    /**
     * Remove a value from the Filter instance.
     * Multiple values will be run in sequence.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function removeValue($value)
    {
        if (($key = array_search($value, $this->values)) !== false) {
            unset($this->values[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove a value from the Filter instance.
     * Multiple values will be run in sequence.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function hasValue($value)
    {
        return in_array($value, $this->values);
    }
}
