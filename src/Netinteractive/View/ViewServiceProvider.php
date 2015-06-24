<?php

namespace Netinteractive\View;

use Illuminate\Support\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;



    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Illuminate\Contracts\View\Factory', 'Netinteractive\View\Factory');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('Illuminate\Contracts\View\Factory');
    }


}
