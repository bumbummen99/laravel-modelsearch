<?php
namespace SkyRaptor\Tests\ModelSearch;

use PHPUnit\Framework\Assert;
use Orchestra\Testbench\TestCase;

use ModelSearch\ModelSearchServiceProvider;
use ModelSearch\Models\ExampleModel;
use ModelSearch\ModelSearch;

class CartTest extends TestCase
{
    /**
     * Set the package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ModelSearchServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
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
        $app['config']->set('modelsearch.filtersFQDN', 'ModelSearch\\Filters\\');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        foreach(range(1, 10) as $index) {
            $exampleModel = new ExampleModel();
            $exampleModel->save();
        }
    }

    /** @test */
    public function it_can_find_a_model_by_id()
    {
        $search = new ModelSearch( ExampleModel::class );
        $search->addFilter('HasId', 1);
        $result = $search->result();

        $this->assertEquals($result->count(), 1);
        foreach( $result as $exampleModel ) {
            $this->assertEquals($exampleModel->id, 1);
        }
    }

    /** @test */
    public function it_can_sort_models_with_a_special_filter()
    {
        $search = new ModelSearch( ExampleModel::class );
        $search->addFilter('SortBy', 'id');
        $result = $search->result();

        $this->assertEquals($result->count(), 10);
        
        $predictedIndex = 1;
        foreach( $result as $exampleModel ) {
            $this->assertEquals($exampleModel->id, $predictedIndex);
            $predictedIndex++; //increase index
        }
    }

    /** @test */
    public function it_can_sort_models_with_a_special_filter_dec()
    {
        $search = new ModelSearch( ExampleModel::class );
        $search->addFilter('SortBy', 'idDesc');
        $result = $search->result();

        $this->assertEquals($result->count(), 10);
        
        $predictedIndex = 10;
        foreach( $result as $exampleModel ) {
            $this->assertEquals($exampleModel->id, $predictedIndex);
            $predictedIndex--; //decrease index
        }
    }
}