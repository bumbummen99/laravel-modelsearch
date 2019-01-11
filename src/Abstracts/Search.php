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


    function __construct()
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
        foreach( $this->filters as $filterName => $value )
        {
            if ($filterName == 'SortBy') //SortBy will be applied in sort pass
                continue;
            
            $filterClass = $this->getFilterFQCN($filterName);
            if (class_exists($filterClass)) {
                $this->builder = $filterClass::apply($this->builder, $value);
            }
        }
    }

    /**
     * Last pass used to sort the result
     * 
     * @return void
     */
    protected function sortPass()
    {
        if ($this->hasFilter('SortBy'))
        {
            $filterClass = $this->getFilterFQCN('SortBy');
            if (class_exists($filterClass)) {
                $this->builder = $filterClass::apply($this->builder, $this->filters['SortBy']);
            }
        }
    }

    /*--------------------------------------------------------------------------------------*\
    |* Interface *|
    \*--------------------------------------------------------------------------------------*/

    /**
     * Runs the search with every filter returning a Collection
     * 
     * @return Illuminate\Support\Collection
     */
    public abstract function result();

    /**
     * Adds every filter in the array to the Search.
     * 
     * @return void
     */
    public function addFilters( array $filters = [] )
    {
        foreach( $filters as $filterName => $value )
        {
            $this->addFilter( $filterName, $value );
        }
    }

    /**
     * Adds the provided filter to the Search.
     * 
     * @return void
     */
    public function addFilter( string $filterName, $value )
    {
        $this->filters[$filterName] = $value;
    }

    /**
     * Checks if the given filter is registered.
     * 
     * @return boolean
     */
    public function hasFilter( string $filterName )
    {
        return array_key_exists( $filterName, $this->filters );
    }

    /**
     * Changes the request filter prefix.
     * 
     * @return boolean
     */
    public function setRequestFilterPrefix( string $requestFilterPrefix )
    {
        $this->requestFilterPrefix = $requestFilterPrefix;
    }

    /*--------------------------------------------------------------------------------------*\
    |* Helpers *|
    \*--------------------------------------------------------------------------------------*/

    /**
     * Runs every filter in the request parameters
     * startign with the requestFilterPrefix to the Search.
     * 
     * @return void
     */
    public function addRequestFilters( Request $request )
    {
        foreach ($request->all() as $filterNameRaw => $value) {
            if ( substr( $filterNameRaw, 0, strlen($this->requestFilterPrefix) ) === $this->requestFilterPrefix )
            {
                $filterName = substr( $filterNameRaw, strlen($this->requestFilterPrefix) );
                if (is_array($value)) 
                    foreach($value as $v)
                        $this->addFilter( $filterName, $v );
                else
                    $this->addFilter( $filterName, $value );
            }
        }
    }

    /**
     * Returns the FQCN of the filter based on filter name.
     * 
     * @return string
     */
    protected function getFilterFQCN( string $filterName )
    {
        return config('modelsearch.filtersFQDN') . $this->modelName . '\\' . studly_case($filterName);
    }
}
