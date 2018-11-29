<?php
//Based on https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db

namespace ModelSearch;

use Illuminate\Database\Eloquent\Model;

use ModelSearch\Abstracts\Search;

class ModelSearch extends Search
{
    private $model;

    /**
     * Process applied filters and get result.
     * 
     * @param $model Illuminate\Database\Eloquent\Model
     * 
     * @return Illuminate\Support\Collection
     */
    public function __construct( Model $model )
    {
        parent::__construct();
        $this->model = $model;
        $this->modelName = ( new \ReflectionClass($this->model) )->getShortName();
    }

    /**
     * Runs every filter in the array on the Collection.
     * 
     * @return Illuminate\Support\Collection
     */
    public function result()
    {
        $this->data = $this->model->newQuery();

        $this->filterPass();
        $this->sortPass();

        return $this->data->get();
    }
}
