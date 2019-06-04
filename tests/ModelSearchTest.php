<?php

namespace SkyRaptor\Tests\ModelSearch;

use ModelSearch\Models\ExampleModel;
use ModelSearch\ModelSearch;
use ModelSearch\ModelSearchServiceProvider;
use Orchestra\Testbench\TestCase;

class ModelSearchTest extends TestCase
{
    /**
     * Set the package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ModelSearchServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup ModelSearch specific config values
        $app['config']->set('modelsearch.filtersFQCN', 'ModelSearch\\Filters\\');
        $app['config']->set('modelsearch.requestFilterPrefix', 'filter_');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../src/Database/migrations');

        foreach (range(1, 10) as $index) {
            $exampleModel = new ExampleModel();
            $exampleModel->name = 'Test';
            $exampleModel->save();
        }

        $exampleModel = new ExampleModel();
        $exampleModel->name = 'Labels';
        $exampleModel->label_one = false;
        $exampleModel->label_two = true;
        $exampleModel->save();

        $exampleModel = new ExampleModel();
        $exampleModel->name = 'Labels';
        $exampleModel->label_one = true;
        $exampleModel->label_two = false;
        $exampleModel->save();

        foreach (range(1, 4) as $index) {
            $exampleModel = new ExampleModel();
            $exampleModel->name = 'Labels';
            $exampleModel->label_one = true;
            $exampleModel->label_two = true;
            $exampleModel->save();
        }
    }

    /** @test */
    public function can_change_config_values()
    {
        $this->app['config']->set('modelsearch.filtersFQCN', 'Changed\\FQCN\\');
        $this->app['config']->set('modelsearch.requestFilterPrefix', 'f_');

        $this->assertEquals('Changed\\FQCN\\', $this->app['config']->get('modelsearch.filtersFQCN'));
        $this->assertEquals('f_', $this->app['config']->get('modelsearch.requestFilterPrefix'));
    }

    /** @test */
    public function it_can_find_a_model_by_id()
    {
        $search = new ModelSearch(ExampleModel::class);
        $search->addFilter('HasId', 1);
        $result = $search->result();

        $this->assertEquals($result->count(), 1);
        foreach ($result as $exampleModel) {
            $this->assertEquals($exampleModel->id, 1);
        }
    }

    /** @test */
    public function it_can_sort_models_with_a_special_filter()
    {
        $search = new ModelSearch(ExampleModel::class);
        $search->addFilter('SortBy', 'id');
        $result = $search->result();

        $predictedIndex = 1;
        foreach ($result as $exampleModel) {
            $this->assertEquals($exampleModel->id, $predictedIndex);
            $predictedIndex++; //increase index
        }
    }

    /** @test */
    public function it_can_sort_models_with_a_special_filter_dec()
    {
        $search = new ModelSearch(ExampleModel::class);
        $search->addFilter('SortBy', 'idDesc');
        $result = $search->result();

        $predictedIndex = 16;
        foreach ($result as $exampleModel) {
            $this->assertEquals($exampleModel->id, $predictedIndex);
            $predictedIndex--; //decrease index
        }
    }

    /** @test */
    public function it_can_add_filters_from_array()
    {
        $search = new ModelSearch(ExampleModel::class);
        $search->addFilters([
            'HasName' => 'Test',
            'SortBy'  => 'idDesc',
            ]);
        $result = $search->result();

        $this->assertEquals($result->count(), 10);
        foreach ($result as $exampleModel) {
            $this->assertEquals('Test', $exampleModel->name);
        }
    }

    /** @test */
    public function it_can_add_filters_from_request()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'filter_hasid' => 1,
        ]);

        $this->assertEquals(true, $request->has('filter_hasid'));
        $this->assertEquals(1, $request->get('filter_hasid'));

        $search = new ModelSearch(ExampleModel::class);
        $search->addRequestFilters($request);
        $result = $search->result();

        $this->assertEquals($result->count(), 1);
        foreach ($result as $exampleModel) {
            $this->assertEquals(1, $exampleModel->id);
        }
    }

    /** @test */
    public function it_can_add_filters_from_request_array_value()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'filter_has_label' => [
                'one',
                'two',
            ],
        ]);

        $this->assertEquals(true, $request->has('filter_has_label'));
        $filterValues = $request->get('filter_has_label');
        $this->assertEquals(true, in_array('one', $filterValues));
        $this->assertEquals(true, in_array('two', $filterValues));

        $search = new ModelSearch(ExampleModel::class);
        $search->addRequestFilters($request);
        $result = $search->result();

        $this->assertEquals($result->count(), 4);
        foreach ($result as $exampleModel) {
            $this->assertEquals(true, $exampleModel->label_one);
            $this->assertEquals(true, $exampleModel->label_two);
        }
    }

    /** @test */
    public function it_can_change_request_filter_prefix_during_runtime()
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'f_hasid' => 1,
        ]);

        $search = new ModelSearch(ExampleModel::class);
        $search->setRequestFilterPrefix('f_');
        $search->addRequestFilters($request);
        $result = $search->result();

        $this->assertEquals($result->count(), 1);
        foreach ($result as $exampleModel) {
            $this->assertEquals(1, $exampleModel->id);
        }
    }

    /**
     * @test
     */
    public function it_will_validate_the_fqcn()
    {
        $this->expectException(\ModelSearch\Exceptions\InvalidModelFQCNException::class);

        $search = new ModelSearch('False\\FQCN');
    }
}
