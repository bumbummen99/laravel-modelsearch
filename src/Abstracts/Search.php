<?php
//Based on https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db

namespace ModelSearch\Abstracts;

use Illuminate\Http\Request;

abstract class Search
{
    protected $modelName;
    protected $filters = [];
    protected $data;

    /**
     * Runs every filter in the array on the Collection.
     * 
     * @return Illuminate\Support\Collection
     */
    public abstract function result();

    /**
     * Runs every filter in the array on the Collection.
     * 
     * @return void
     */
    public function addRequestFilters( Request $request )
    {
        foreach ($request->all() as $filterNameRaw => $value) {
            if ( substr( $filterNameRaw, 0, 2 ) === "f_" )
            {
                $filterName = substr( $filterNameRaw, 2 );
                $this->addFilter( $filterName, $value );
            }
        }
    }

    /**
     * Runs every filter in the array on the Collection.
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
     * Runs every filter in the array on the Collection.
     * 
     * @return void
     */
    public function addFilter( string $filterName, $value )
    {
        $this->filters[$filterName] = $value;
    }

    /**
     * Checks if the given filter si registered
     * 
     * @return boolean
     */
    public function hasFilter( string $filterName )
    {
        return array_key_exists( $filterName, $this->filters );
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
                $this->data = $filterClass::apply($this->data, $value);
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
                $this->data = $filterClass::apply($this->data, $this->filters['SortBy']);
            }
        }
    }

    /*
     * Helpers
     */

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
