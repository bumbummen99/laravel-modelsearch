<?php
//Based on https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db

namespace ModelSearch;

use ModelSearch\Abstracts\Search;
use ModelSearch\Exceptions\InvalidModelFQCNException;

class ModelSearch extends Search
{
    private $model;
    private $modelFQCN;

    /**
     * Process applied filters and get result.
     * 
     * @param $model Illuminate\Database\Eloquent\Model
     * 
     * @return Illuminate\Support\Collection
     */
    public function __construct( string $modelFQCN )
    {
        parent::__construct();

        $this->modelFQCN = $modelFQCN;
        if (!class_exists($this->modelFQCN))
            throw new InvalidModelFQCNException("The provided model FQCN class does not exist.");

        $this->modelName = ( new \ReflectionClass($this->modelFQCN) )->getShortName();
    }

    /**
     * Runs every filter in the array on the Collection.
     * 
     * @return Illuminate\Support\Collection
     */
    public function result()
    {
        $this->builder = $this->modelFQCN::query();

        $this->filterPass();
        $this->sortPass();

        return $this->builder->get();
    }
}
