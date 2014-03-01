<?php namespace Hskrasek\EloquentSoftdeleteCascader;

use Illuminate\Support\ServiceProvider;

class EloquentSoftdeleteCascaderServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('hskrasek/eloquent-softdelete-cascader');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCascader();
        $this->registerEvents();
    }

    protected function registerCascader()
    {
        $this->app['cascader'] = $this->app->share(function ($app) {
            return new Cascader();
        });
    }

    protected function registerEvents()
    {
        $app = $this->app;

        $app['events']->listen('eloquent.deleted*', function($model) use ($app)
        {
            $app['cascader']->make($model);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('cascader');
    }

}