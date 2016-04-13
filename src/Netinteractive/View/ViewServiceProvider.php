<?php

namespace Netinteractive\View;

class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('/packages/netinteractive/view/config.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $config = realpath(__DIR__ . '/../../config/config.php');

        $this->mergeConfigFrom($config, 'packages.netinteractive.view.config');

        parent::register();
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('view', function($app)
        {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $env = new Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $env->setContainer($app);

            $env->share('app', $app);

            return $env;
        });
    }
}
