<?php

namespace henry\sw1;

use Blade;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class sw1ServiceProvider extends ServiceProvider
{
    /**
     * Indicates of loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sW1.php' => config_path('sW1.php'),
        ]);

        if (version_compare(Application::VERSION, '5.3.0', '<')) {
            $this->publishes([
                __DIR__ . '/../migrations' => $this->app->databasePath() . '/migrations',
            ], 'migrations');
        } else {
            if (config('sW1.run-migrations', true)) {
                $this->loadMigrationsFrom(__DIR__ . '/../migrations');
            }
        }

        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sW1.php', 'sW1'
        );

        $this->app->singleton('sW1', function ($app) {
            $auth = $app->make('Illuminate\Contracts\Auth\Guard');

            return new \henry\sw1\sw1($auth);
        });
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('can', function ($expression) {
            return "<?php if (\\sw1::can({$expression})): ?>";
        });

        Blade::directive('endcan', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('canatleast', function ($expression) {
            return "<?php if (\\sw1::canAtLeast({$expression})): ?>";
        });

        Blade::directive('endcanatleast', function ($expression) {
            return '<?php endif; ?>';
        });

        Blade::directive('role', function ($expression) {
            return "<?php if (\\sw1::isRole({$expression})): ?>";
        });

        Blade::directive('endrole', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sW1'];
    }
}
