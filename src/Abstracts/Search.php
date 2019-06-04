<?php

//Based on https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db

namespace ModelSearch\Abstracts;

use Illuminate\Http\Request;

abstract class Search
{
    protected $modelName;
    protected $filters = [];
    protected $builder;
    protected $requestFilterPrefix;

    public function __construct()
    {
        $this->requestFilterPrefix = config('modelsearch.requestFilterPrefix');
    }

    /**
     * Runs every filter in the array on the Collection.
     *
     * @return void
     */
    protected function filterPass()
    {
        foreach ($this->filters as $filterName => $filter) {
            if ($filterName == 'SortBy') { //SortBy will be applied in sort pass
                continue;
            }

            $this->builder = $filter->run($this->builder);
        }
    }

    /**
     * Last pass used to sort the result.
     *
     * @return void
     */
    protected function sortPass()
    {
        if ($this->hasFilter('SortBy')) {
            $this->builder = $this->filters['SortBy']->run($this->builder);
        }
    }

    /*--------------------------------------------------------------------------------------*\
    |* Interface *|
    \*--------------------------------------------------------------------------------------*/

    /**
     * Runs the search with every filter returning a Collection.
     *
     * @return Illuminate\Support\Collection
     */
    abstract public function result();

    /**
     * Adds every filter in the array to the Search.
     *
     * @return void
     */
    public function addFilters(array $filters = [])
    {
        foreach ($filters as $filterName => $value) {
            $this->addFilter($filterName, $value);
        }
    }

    /**
     * Adds the provided filter to the Search.
     *
     * @return string|bool
     */
    public function addFilter(string $filterName, $value)
    {
        $fqcn = $this->getFilterFQCN($filterName);
        if (class_exists($fqcn)) {
            $className = studly_case($filterName);
            $this->filters[studly_case($className)] = new $fqcn($value);
            return $className;
        } else {
            return false;
        }
    }

    /**
     * Checks if the given filter is registered.
     *
     * @return bool
     */
    public function hasFilter(string $filterName)
    {
        return array_key_exists(studly_case($filterName), $this->filters);
    }

    /**
     * Changes the request filter prefix.
     *
     * @return bool
     */
    public function setRequestFilterPrefix(string $requestFilterPrefix)
    {
        $this->requestFilterPrefix = $requestFilterPrefix;
    }

    /*--------------------------------------------------------------------------------------*\
    |* Helpers *|
    \*--------------------------------------------------------------------------------------*/

    /**
     * Runs every filter in the request parameters
     * starting with the requestFilterPrefix to the Search.
     *
     * @return void
     */
    public function addRequestFilters(Request $request)
    {
        foreach ($request->all() as $filterNameRaw => $value) {
            if (substr($filterNameRaw, 0, strlen($this->requestFilterPrefix)) === $this->requestFilterPrefix) {
                $filterName = substr($filterNameRaw, strlen($this->requestFilterPrefix));
                if (is_array($value)) {
                    $key = $this->addFilter($filterName, array_shift($value));
                    foreach($value as $val) {
                        $this->filters[$key]->addValue($val);
                    }
                } else {
                    $this->addFilter($filterName, $value);
                }   
            }
        }
    }

    /**
     * Returns the FQCN of the filter based on filter name.
     *
     * @return string
     */
    protected function getFilterFQCN(string $filterName)
    {
        return config('modelsearch.filtersFQCN').$this->modelName.'\\'.studly_case($filterName);
    }
}
